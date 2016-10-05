
create table dbo.tf_po_proc(
idtf_po_proc		numeric(20,0) not null identity (1,1),
poCliente			varchar(15),
poNumber			varchar(100),
poVendorId			varchar(100),
poCompany			varchar(100),
poDetailId			varchar(100),
poLineNumber		varchar(100),
itemCode			varchar(30),	
session				varchar(1000),
f_insert			datetime
);
go

alter table dbo.tf_po_proc add constraint pk_tf_po_proc primary key(idtf_po_proc);