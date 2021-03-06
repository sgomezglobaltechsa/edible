Begin try
	drop view dbo.view_trans_edi_recepciones
End Try Begin Catch End Catch
go

create view dbo.view_trans_edi_recepciones
as
SELECT	SDDV.CLIENTE_ID								AS [poClienteId],
		SDDV.CUSTOMS_1								AS [lineId], 
		SDV.ORDEN_DE_COMPRA							AS [poNumber],
		SUM(SDDV.CANTIDAD)							AS [quantity],
		SDDV.UNIDAD_ID								AS [receiveUom],
		SUM(SDDV.CANTIDAD)*P.PESO					AS [weight],
		isnull(P.UNIDAD_PESO,0)						AS [weightUom],
		--CONVERT(VARCHAR, dateadd(hour,-1,SDDV.FECHA_ESTADO_GT),127)	AS [date],
		cast(SDDV.FECHA_ESTADO_GT as datetimeoffset)AS [date],
		CASE ISNULL(BO.QTY,0) 
		WHEN 0 THEN 'N' ELSE 'S' END				AS lock
FROM	SYS_DEV_DOCUMENTO SDV INNER JOIN SYS_DEV_DET_DOCUMENTO SDDV	ON(SDV.CLIENTE_ID=SDDV.CLIENTE_ID AND SDV.DOC_EXT=SDDV.DOC_EXT)
		INNER JOIN PRODUCTO P										ON(SDDV.CLIENTE_ID=P.CLIENTE_ID AND SDDV.PRODUCTO_ID=P.PRODUCTO_ID)
		LEFT JOIN(	SELECT	SDD.CLIENTE_ID, SDD.DOC_EXT, COUNT(SDD.NRO_LINEA) AS QTY
					FROM	SYS_INT_DET_DOCUMENTO SDD INNER JOIN SYS_INT_DOCUMENTO SD ON(SDD.CLIENTE_ID=SD.CLIENTE_ID AND SDD.DOC_EXT=SDD.DOC_EXT)
					WHERE	SD.TIPO_DOCUMENTO_ID='I01'
							AND SDD.ESTADO_GT IS NULL
					GROUP BY
							SDD.CLIENTE_ID, SDD.DOC_EXT)BO			ON(SDDV.CLIENTE_ID=BO.CLIENTE_ID AND SDDV.DOC_EXT=BO.DOC_EXT)						
WHERE	SDV.TIPO_DOCUMENTO_ID='I02'
		AND ISNULL(SDDV.FLG_MOVIMIENTO,'0')='0'
GROUP BY
		SDDV.CLIENTE_ID, SDDV.CUSTOMS_1, SDV.ORDEN_DE_COMPRA, SDDV.UNIDAD_ID, P.PESO, P.UNIDAD_PESO,
		cast(SDDV.FECHA_ESTADO_GT as datetimeoffset)	,CASE ISNULL(BO.QTY,0)  WHEN 0 THEN 'N' ELSE 'S' END