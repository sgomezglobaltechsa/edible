
select * from sys_dev_documento where orden_de_compra in(select ref_1 from LOG_PROCESOS)
select * from sys_dev_det_documento where doc_ext in(select doc_ext from sys_dev_documento where orden_de_compra in(select ref_1 from LOG_PROCESOS))

select * from view_trans_edi_recepciones

select * from LOG_PROCESOS order by 1 desc

select * from sys_int_documento where ORDEN_DE_COMPRA='023794'

delete from SYS_INT_DET_DOCUMENTO where doc_ext in(select doc_ext from SYS_INT_DOCUMENTO where ORDEN_DE_COMPRA in(select ref_1 from LOG_PROCESOS));
delete from SYS_INT_DOCUMENTO where doc_ext in(select doc_ext from SYS_INT_DOCUMENTO where ORDEN_DE_COMPRA in(select ref_1 from LOG_PROCESOS));
delete from LOG_PROCESOS;

select	lineID, poNumber, quantity, receiveUom, weight, weightUom, date  
from	dbo.view_trans_edi_recepciones 
where	poClienteId='PAPIER'


SELECT FLG_MOVIMIENTO FROM SYS_DEV_DET_DOCUMENTO WHERE CUSTOMS_1='00289416'

UPDATE SYS_DEV_DET_DOCUMENTO SET FLG_MOVIMIENTO='0' WHERE DOC_EXT in ('20169191217490')--('20169151759228','20169151759227')

SELECT * FROM LOG_PROCESOS ORDER BY 1 DESC
