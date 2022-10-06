create database db_aulaBan;
use db_aulaBan;

create table tb_sale_items(
    sli_saleItemsId int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    sli_saleId int(11) NOT NULL,
    sli_productId int(11) NOT NULL,
    sli_quantity int(11),
    sli_totalPrice float(12)
);
create table tb_products(
    pr_productId int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    pr_description varchar(255) NOT NULL,
    pr_price float(12) NOT NULL,
    pr_detail varchar(255)
);
create table tb_invoices(
    in_invoiceId int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    in_number int(11) NOT NULL,
    in_serie int(11) NOT NULL,
    in_key int(11) NOT NULL,
    in_date datetime,
    in_price float(12)
);
create table tb_inventory_itens(
    ivt_inventoryItensId int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ivt_inventoryId int(11) NOT NULL,
    ivt_productId int(11) NOT NULL,
    ivt_quantity int(11)
);
create table tb_inventory(
    iv_inventoryId int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    iv_location varchar(50) NOT NULL
);
create table tb_sales(
    sl_saleId int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    sl_customerId int(11) NOT NULL,
    sl_sellerId int(11) NOT NULL,
    sl_date datetime,
    sl_finalPrice float(12),
    sl_quantity int(11),
    sl_invoiceId int(11)
);
create table tb_customer(
    cs_customerId int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    cs_name varchar(50) NOT NULL
);
create table tb_users(
    usu_userId int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    usu_name varchar(50) NOT NULL,
    usu_email varchar(50),
    usu_password varchar(255)
);
create table tb_logs(
    lg_logId int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    lg_userId int(11) NOT NULL,
    lg_date datetime,
    lg_description varchar(255)
);
create table tb_transactions(
    tr_transactionId int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    tr_customerId int(11) NOT NULL,
    tr_paymentId int(11),
    tr_invoiceId int(11),
    tr_date datetime,
    tr_messagem varchar(255)
);
create table tb_payments(
    pa_paymentId int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    pa_type varchar(45) NOT NULL,
    pa_description varchar(45)
);
 -- Adicono as chaves estrangeiras na tabela tb_sale_items
ALTER TABLE tb_sale_items ADD CONSTRAINT sli_saleId
FOREIGN KEY(sli_saleId) REFERENCES tb_sales (sl_saleId);

ALTER TABLE tb_sale_items ADD CONSTRAINT sli_productId
FOREIGN KEY(sli_productId) REFERENCES tb_products (pr_productId);

 -- Adicono as chaves estrangeiras na tabela tb_sales
ALTER TABLE tb_sales ADD CONSTRAINT sl_customerId
FOREIGN KEY(sl_customerId) REFERENCES tb_customer (cs_customerId);

ALTER TABLE tb_sales ADD CONSTRAINT sl_sellerId
FOREIGN KEY(sl_sellerId) REFERENCES tb_users (usu_userId);

ALTER TABLE tb_sales ADD CONSTRAINT sl_invoiceId
FOREIGN KEY(sl_invoiceId) REFERENCES tb_invoices (in_invoiceId);

 -- Adicono as chaves estrangeiras na tabela tb_inventory_itens
ALTER TABLE tb_inventory_itens ADD CONSTRAINT ivt_inventoryId
FOREIGN KEY(ivt_inventoryId) REFERENCES tb_inventory (iv_inventoryId);

ALTER TABLE tb_inventory_itens ADD CONSTRAINT ivt_productId
FOREIGN KEY(ivt_productId) REFERENCES tb_products (pr_productId);

 -- Adicono as chaves estrangeiras na tabela tb_transactions
ALTER TABLE tb_transactions ADD CONSTRAINT tr_customerId
FOREIGN KEY(tr_customerId) REFERENCES tb_customer (cs_customerId);

ALTER TABLE tb_transactions ADD CONSTRAINT tr_paymentId
FOREIGN KEY(tr_paymentId) REFERENCES tb_payments (pa_paymentId);

ALTER TABLE tb_transactions ADD CONSTRAINT tr_invoiceId
FOREIGN KEY(tr_invoiceId) REFERENCES tb_invoices (in_invoiceId);

 -- Adicono as chaves estrangeiras na tabela tb_logs
ALTER TABLE tb_logs ADD CONSTRAINT lg_userId
FOREIGN KEY(lg_userId) REFERENCES tb_users (usu_userId);

-- Crio campos na tabela de pedidos
ALTER TABLE tb_sales ADD sl_paymentId INT;
ALTER TABLE tb_sales ADD sl_statusPayment INT;

 -- Adicono as chaves estrangeiras na tabela tb_sales
ALTER TABLE tb_sales ADD CONSTRAINT sl_paymentId
FOREIGN KEY(sl_paymentId) REFERENCES tb_payments (pa_paymentId);

insert into tb_payments ( pa_type, pa_description) 
VALUES (1, 'Cartão de Crédito');

insert into tb_payments ( pa_type, pa_description) 
VALUES (2, 'Cartão de Débito');

insert into tb_payments ( pa_type, pa_description) 
VALUES (3, 'Boleto Bancário');
select * from tb_payments;
INSERT INTO tb_transactions (tr_customerId, tr_paymentId, tr_invoiceId, tr_date, tr_messagem) 
                VALUES (6, 1, 34, '2022-10-05 00:00:00', 'teste 2');
                
-- delete from tb_customer;
-- select * from tb_customer;
-- select * from tb_inventory;
-- select * from tb_inventory_itens;
-- select * from tb_invoices;
-- select * from tb_products;
-- delete from tb_sales;





use db_aulaBan;
SELECT * from tb_transactions;
SELECT * from tb_payments;
select * from tb_invoices;
SELECT * from tb_logs;

		


