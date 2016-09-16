begin try
	drop procedure dbo.TRANS_EDI_INS_PURCHASE_ORDER
end try begin catch end catch
go

Create procedure dbo.TRANS_EDI_INS_PURCHASE_ORDER
@poCliente			varchar(15),
@poNumber			varchar(100),
@poVendorId			varchar(100),
@poCompany			varchar(100),
@poDetailId			varchar(100),	--OK
@poLineNumber		varchar(100),	--OK
@itemCode			varchar(30),	--OK
@itemDescription	varchar(500),	--OK
@orderQuantity		varchar(100),	--OK
@orderUom			varchar(100),	--OK
@weight				varchar(100),	--OK
@weightUom			varchar(100),
@session			varchar(1000)
As
Begin

	declare @vdoc_ext			varchar(100)
	declare @seq				numeric(38)
	declare @comando			varchar(4000)
	declare @existe				numeric(20)
	declare @vitemDescription	varchar(500)
	begin try

		select	@existe=count(*)
		from	SYS_INT_DET_DOCUMENTO
		where	cliente_id=@poCliente
				and producto_id	= @itemCode
				and CUSTOMS_1	= @poDetailId
				and CUSTOMS_2	= @poLineNumber

		if @existe=0 begin

			------------------------------------------------------------------------------------------------------------------------------------------------------
			--Armo el comando de insert.
			------------------------------------------------------------------------------------------------------------------------------------------------------
			set @comando=	'EXEC dbo.TRANS_EDI_INS_PURCHASE_ORDER ' 
							+ CHAR(39) +	@poCliente			+ CHAR(39) + ','
							+ CHAR(39) +	@poNumber			+ CHAR(39) + ','
							+ CHAR(39) +	@poVendorId			+ CHAR(39) + ','
							+ CHAR(39) +	@poCompany			+ CHAR(39) + ','
							+ CHAR(39) +	@poDetailId			+ CHAR(39) + ','
							+ CHAR(39) +	@poLineNumber		+ CHAR(39) + ','
							+ CHAR(39) +	@itemCode			+ CHAR(39) + ','
							+ CHAR(39) +	@itemDescription	+ CHAR(39) + ','
							+ CHAR(39) +	@orderQuantity		+ CHAR(39) + ','
							+ CHAR(39) +	@orderUom			+ CHAR(39) + ','
							+ CHAR(39) +	@weight				+ CHAR(39) + ','
							+ CHAR(39) +	@weightUom			+ CHAR(39) + ','
							+ CHAR(39) +	@session			+ CHAR(39) + ';'

		

			exec dbo.GET_VALUE_FOR_SEQUENCE 'ADD_DOC_EXT',@seq output

			set	@vdoc_ext=	cast(datepart(yyyy,getdate())as varchar)+cast(datepart(month,getdate())as varchar)+cast(datepart(day,getdate())as varchar)
							+cast(datepart(hour,getdate())as varchar)+cast(datepart(minute,getdate())as varchar)+cast(@seq as varchar)

			exec DBO.TRANS_EDI_INS_CABECERA	@poCliente,	@vdoc_ext,	@poNumber,	@poVendorId,	@poCompany,	@session



			set @vitemDescription=ltrim(rtrim(upper(@itemDescription)))

			exec dbo.TRANS_EDI_INS_DETALLE	@poCliente,		@vdoc_ext,	@poDetailId,	@poLineNumber,	@itemCode,	@vitemDescription,
											@orderQuantity,	@orderUom,	@weight,		@weightUom,		@session

			EXEC DBO.INS_LOG_PROCESO	'TRANS_EDI_INS_PURCHASE_ORDER',@comando,'OK',NULL,@poNumber, @session	
		
		end --fin: if @existe=0

	end try
	begin catch

	end catch
End --fin: procedure dbo.TRANS_EDI_INS_PURCHASE_ORDER