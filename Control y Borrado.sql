
select * from sys_dev_documento where orden_de_compra in(select ref_1 from LOG_PROCESOS)
select * from sys_dev_det_documento where doc_ext in(select doc_ext from sys_dev_documento where orden_de_compra in(select ref_1 from LOG_PROCESOS))

select * from view_trans_edi_recepciones

select * from LOG_PROCESOS order by 1 desc

select * from sys_int_documento where ORDEN_DE_COMPRA='023794'

delete from SYS_INT_DET_DOCUMENTO where doc_ext in(select doc_ext from SYS_INT_DOCUMENTO where ORDEN_DE_COMPRA in(select ref_1 from LOG_PROCESOS));
delete from SYS_INT_DOCUMENTO where doc_ext in(select doc_ext from SYS_INT_DOCUMENTO where ORDEN_DE_COMPRA in(select ref_1 from LOG_PROCESOS));
delete from LOG_PROCESOS;

