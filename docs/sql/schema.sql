
CREATE TABLE IF NOT EXISTS Tenants (
  account_id SERIAL PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  phone VARCHAR(15) NOT NULL
);

CREATE TABLE IF NOT EXISTS Apartments (
  apartment_id SERIAL PRIMARY KEY,
  address VARCHAR(255) NOT NULL,
  residents INT NOT NULL,
  area DECIMAL(10,2) NOT NULL,
  account_id INT UNIQUE REFERENCES Tenants(account_id)
);

CREATE TABLE IF NOT EXISTS Services (
  service_id SERIAL PRIMARY KEY,
  service_type VARCHAR(50) NOT NULL,
  unit VARCHAR(10) NOT NULL,
  tariff DECIMAL(10,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS Payments (
  payment_id SERIAL PRIMARY KEY,
  account_id INT REFERENCES Tenants(account_id),
  service_id INT REFERENCES Services(service_id),
  consumed DECIMAL(10,2) NOT NULL,
  due_date DATE NOT NULL,
  paid_on_time BOOLEAN NOT NULL,
  payment_date DATE
);
