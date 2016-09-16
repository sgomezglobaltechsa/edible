begin try
	drop procedure dbo.TRANS_EDI_INS_DETALLE
end try begin catch end catch
go

Create procedure dbo.TRANS_EDI_INS_DETALLE
@poCliente			varchar(15),	--OK
@doc_ext			varchar(100),	--OK
@poDetailId			varchar(100),	--OK
@poLineNumber		varchar(100),	--OK
@itemCode			varchar(30),	--OK
@itemDescription	varchar(500),	--OK
@orderQuantity		varchar(100),	--OK
@orderUom			varchar(100),	--OK
@weight				varchar(100),	--OK
@weightUom			varchar(100),
@session			varchar(1000)
as
Begin
	declare @comando	varchar(1000)
	declare @error		varchar(4000)
	declare @Ctn		numeric(20,0)
	declare @vnro_linea	numeric(10,0)
	begin try

		------------------------------------------------------------------------------------------------------------------------------------------------------
		--unidades de medida.
		------------------------------------------------------------------------------------------------------------------------------------------------------
		Set @orderUom=ltrim(rtrim(upper(@orderUom)))

		select	@ctn=count(*)
		from	UNIDAD_MEDIDA
		where	unidad_id=@orderUom

		if @Ctn=0 begin
			insert into UNIDAD_MEDIDA (unidad_id,DESCRIPCION)values(@orderUom, @orderUom)
		end

		Set @weightUom=ltrim(rtrim(upper(@weightUom)))

		select	@ctn=count(*)
		from	UNIDAD_MEDIDA
		where	unidad_id=@weightUom

		if @Ctn=0 begin
			insert into UNIDAD_MEDIDA (unidad_id,DESCRIPCION)values(@weightUom, @weightUom)
		end
		------------------------------------------------------------------------------------------------------------------------------------------------------		
		--puede ser que necesite cargar el producto.
		------------------------------------------------------------------------------------------------------------------------------------------------------
		/*
		insert into sys_int_det_documento(	
				doc_ext,	nro_linea,	cliente_id,		producto_id,	cantidad_solicitada,	cantidad,	descripcion,		unidad_id,	peso,		customs_1,		customs_2,		unidad_peso)
		values(	@poNumber,	1,			@poCliente,		@itemCode,		@orderQuantity,			0,			@itemDescription,	@orderUom,	@weight,	@poDetailId,	@poLineNumber,	@weightUom)*/
		
		select	@vnro_linea=max(isnull(nro_linea,0))+1
		from	sys_int_det_documento
		where	cliente_id=@poCliente
				and doc_ext=@doc_ext

		if @vnro_linea is null begin
			set @vnro_linea=1
		end


		EXEC [dbo].[SYS_INT_DET_DOC]	@DOC_EXT				= @doc_ext,				@NRO_LINEA				= @vnro_linea,		@CLIENTE_ID				= @poCliente,			
										@PRODUCTO_ID			= @itemCode,			@CANTIDAD_SOLICITADA	= @orderQuantity,	@CANTIDAD				= 0,
										@EST_MERC_ID			= NULL,					@CAT_LOG_ID				= NULL,				@NRO_BULTO				= NULL,
										@DESCRIPCION			= @itemDescription,		@NRO_LOTE				= NULL,				@NRO_PALLET				= NULL,
										@FECHA_VENCIMIENTO		= NULL,					@NRO_DESPACHO			= NULL,				@NRO_PARTIDA			= NULL,
										@UNIDAD_ID				= @orderUom,			@UNIDAD_CONTENEDORA_ID	= NULL,				@PESO					= @weight,
										@UNIDAD_PESO			= @weightUom,			@VOLUMEN				= NULL,				@UNIDAD_VOLUMEN			= NULL,
										@PROP1					= NULL,					@PROP2					= NULL,				@PROP3					= NULL,
										@LARGO					= NULL,					@ALTO					= NULL,				@ANCHO					= NULL,
										@DOC_BACK_ORDER			= NULL,					@ESTADO					= NULL,				@FECHA_ESTADO			= NULL,
										@ESTADO_GT				= NULL,					@FECHA_ESTADO_GT		= NULL,				@DOCUMENTO_ID			= NULL,
										@NAVE_ID				= NULL,					@NAVE_COD				= NULL,				@CUSTOMS_1				= @poDetailId,
										@CUSTOMS_2				= @poLineNumber,		@CUSTOMS_3				= NULL


	end try
	begin catch
		------------------------------------------------------------------------------------------------------------------------------------------------------
		--log de errores.
		------------------------------------------------------------------------------------------------------------------------------------------------------
		set @error=cast(ERROR_MESSAGE() as varchar(4000));
		EXEC DBO.INS_LOG_PROCESO	'TRANS_EDI_INS_DETALLE',null,'ERR',@error ,@doc_ext, @session	
	end catch
end