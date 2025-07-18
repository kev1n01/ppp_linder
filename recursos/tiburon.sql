
CREATE TABLE customers
(
  id          BIGINT  NOT NULL,
  user_id     BIGINT  NOT NULL,
  cu_name     varchar NOT NULL,
  cu_num_doc  varchar NOT NULL,
  cu_type_doc varchar NULL     COMMENT 'dni, ruc',
  cu_email    varchar NULL    ,
  cu_address  varchar NULL    ,
  cu_phone    varchar NULL    ,
  cu_status   boolean NOT NULL DEFAULT 1,
  PRIMARY KEY (id)
);

CREATE TABLE employees
(
  id            BIGINT  NOT NULL,
  user_id       BIGINT  NOT NULL,
  emp_num_doc   varchar NOT NULL,
  emp_email     varchar NULL    ,
  emp_address   varchar NULL    ,
  emp_status    boolean NOT NULL DEFAULT 1,
  emp_birthdate date    NULL    ,
  PRIMARY KEY (id)
);

CREATE TABLE items
(
  id              BIGINT  NOT NULL,
  ite_name        varchar NOT NULL,
  ite_description text    NULL    ,
  ite_price       numeric NOT NULL,
  ite_status      boolean NOT NULL DEFAULT 1,
  ite_type        varchar NOT NULL COMMENT 'producto, servicio',
  ite_image       varchar NULL    ,
  PRIMARY KEY (id)
);

CREATE TABLE method_pay_accounts
(
  id            BIGINT  NOT NULL,
  mpa_name      varchar NOT NULL,
  mpa_cc_numer  varchar NULL    ,
  mpa_cci_numer varchar NULL    ,
  mpa_phone_num varchar NULL    ,
  mpa_type      varchar NOT NULL COMMENT 'digital, bancario',
  PRIMARY KEY (id)
);

CREATE TABLE model_has_roles
(
  role_id    BIGINT NOT NULL,
  model_type        NOT NULL,
  model_id   BIGINT NOT NULL
);

CREATE TABLE permissions
(
  id         BIGINT  NOT NULL,
  name       varchar NOT NULL,
  guard_name varchar NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE role_has_permissions
(
  role_id       BIGINT NOT NULL,
  permission_id BIGINT NOT NULL
);

CREATE TABLE roles
(
  id         BIGINT  NOT NULL,
  name       varchar NOT NULL,
  guard_name varchar NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE sale
(
  id                 BIGINT  NOT NULL,
  customer_id        BIGINT  NOT NULL,
  employee_id        BIGINT  NOT NULL,
  sal_total_amount   numeric NOT NULL,
  sal_payment_method varchar NOT NULL COMMENT 'efectivo o tarjeta',
  sal_date           date    NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE sale_details
(
  id            BIGINT  NOT NULL,
  item_id       BIGINT  NOT NULL,
  sale_id       BIGINT  NOT NULL,
  sald_quantity integer NOT NULL,
  sald_price    numeric NOT NULL,
  sald_subtotal numeric NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE settings
(
  id                BIGINT  NOT NULL,
  set_name_business varchar NOT NULL,
  set_ruc           varchar NOT NULL,
  set_province      varchar NOT NULL,
  set_phone         varchar NOT NULL,
  set_department    varchar NOT NULL,
  set_district      varchar NOT NULL,
  set_ubigeous      varchar NOT NULL,
  set_address       varchar NOT NULL,
  set_logo          varchar NULL    ,
  PRIMARY KEY (id)
);

CREATE TABLE users
(
  id                BIGINT  NOT NULL,
  name              varchar NOT NULL,
  email             varchar NOT NULL,
  email_verified_at date    NULL    ,
  password          varchar NULL    ,
  PRIMARY KEY (id)
);

ALTER TABLE sale_details
  ADD CONSTRAINT FK_items_TO_sale_details
    FOREIGN KEY (item_id)
    REFERENCES items (id);

ALTER TABLE sale_details
  ADD CONSTRAINT FK_sale_TO_sale_details
    FOREIGN KEY (sale_id)
    REFERENCES sale (id);

ALTER TABLE sale
  ADD CONSTRAINT FK_customers_TO_sale
    FOREIGN KEY (customer_id)
    REFERENCES customers (id);

ALTER TABLE sale
  ADD CONSTRAINT FK_employees_TO_sale
    FOREIGN KEY (employee_id)
    REFERENCES employees (id);

ALTER TABLE employees
  ADD CONSTRAINT FK_users_TO_employees
    FOREIGN KEY (user_id)
    REFERENCES users (id);

ALTER TABLE customers
  ADD CONSTRAINT FK_users_TO_customers
    FOREIGN KEY (user_id)
    REFERENCES users (id);

ALTER TABLE model_has_roles
  ADD CONSTRAINT FK_users_TO_model_has_roles
    FOREIGN KEY (model_id)
    REFERENCES users (id);

ALTER TABLE model_has_roles
  ADD CONSTRAINT FK_roles_TO_model_has_roles
    FOREIGN KEY (role_id)
    REFERENCES roles (id);

ALTER TABLE role_has_permissions
  ADD CONSTRAINT FK_roles_TO_role_has_permissions
    FOREIGN KEY (role_id)
    REFERENCES roles (id);

ALTER TABLE role_has_permissions
  ADD CONSTRAINT FK_permissions_TO_role_has_permissions
    FOREIGN KEY (permission_id)
    REFERENCES permissions (id);
