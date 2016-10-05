begin try
	drop procedure dbo.tf_LimpiaPO_NoProcesadas
end try begin catch end catch
go

create procedure dbo.tf_LimpiaPO_NoProcesadas
@cliente_id	varchar(30)
as
begin

	delete from SYS_INT_DET_DOCUMENTO
	from	sys_int_documento sda inner join SYS_INT_DET_DOCUMENTO sdda	on(sda.CLIENTE_ID=sdda.CLIENTE_ID and sda.DOC_EXT=sdda.DOC_EXT)
			left join (select	cliente_id, 
								doc_ext, 
								count(nro_linea)as proc_op
						from	SYS_INT_DET_DOCUMENTO
						where	estado_gt is not null
								and fecha_estado_gt is not null
						group by
								cliente_id, doc_ext)procesado
																		on(sda.CLIENTE_ID=procesado.cliente_id and sda.doc_ext=procesado.DOC_EXT)
	where	SDa.CLIENTE_ID=@cliente_id
			AND SDa.TIPO_DOCUMENTO_ID='I01'
			and isnull(procesado.proc_op,0)=0
			and not exists(	select	sd.DOC_EXT
							from	sys_int_documento sd inner join SYS_INT_DET_DOCUMENTO sdd	on(sd.CLIENTE_ID=sdd.CLIENTE_ID and sd.DOC_EXT=sdd.DOC_EXT)
									inner join dbo.tf_po_proc tf								
									on(tf.poCliente=sd.CLIENTE_ID and tf.poNumber=sd.ORDEN_DE_COMPRA and ltrim(rtrim(tf.poVendorId))=sd.AGENTE_ID and tf.poDetailId=sdd.customs_1 and tf.poLineNumber=sdd.CUSTOMS_2 and tf.itemCode=sdd.PRODUCTO_ID)
							where	SD.CLIENTE_ID=@cliente_id
									AND SD.TIPO_DOCUMENTO_ID='I01'
									and sd.CLIENTE_ID=sda.CLIENTE_ID
									and sd.DOC_EXT=sda.DOC_EXT)

	delete from dbo.tf_po_proc where poCliente=@cliente_id

end