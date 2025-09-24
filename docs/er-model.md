

Сущности и атрибуты:

- КВАРТИРОСЪЕМЩИК (Tenant)
  - account_id (PK)
  - full_name
  - phone

- КВАРТИРА (Apartment)
  - apartment_id (PK)
  - address
  - residents
  - area
  - account_id (UK, FK -> Tenant.account_id)

- УСЛУГА (Service)
  - service_id (PK)
  - service_type
  - unit
  - tariff

- ОПЛАТА (Payment)
  - payment_id (PK)
  - account_id (FK -> Tenant.account_id)
  - service_id (FK -> Service.service_id)
  - consumed
  - due_date
  - paid_on_time
  - payment_date (nullable)

Связи и кардинальности:

- Tenant — Apartment: 1:1 (обязательная с обеих сторон), ключ Apartment.account_id уникальный и ссылается на Tenant.account_id.
- Tenant — Payment: 1:N (Tenant обязан иметь платежи).
- Service — Payment: 1:N (Service может не иметь платежей — необязательная со стороны Service).

Обязательность:

- Каждый квартиросъёмщик обязательно снимает квартиру — связь 1:1 обязательная.
- Каждая квартира обязательно имеет квартиросъёмщика — связь 1:1 обязательная.
- Каждый квартиросъёмщик обязательно производит оплату за услугу — для проектов часто контролируется бизнес-логикой.
- Услуга может быть ни разу никому не оказана — связь Service→Payment необязательная со стороны Service.

Простая ASCII-диаграмма:

```
Tenant (account_id PK, full_name, phone)
   || 1..1
   ||
Apartment (apartment_id PK, address, residents, area, account_id UK, FK -> Tenant.account_id)

Tenant (1) ------< (N) Payment (payment_id PK, account_id FK, service_id FK, consumed, due_date, paid_on_time, payment_date)

Service (1) -----< (N) Payment
(service_id PK, service_type, unit, tariff)
```

Правила пени:
- Сумма = consumed * tariff.
- Пеня = 0.1% в день от суммы за каждый день после due_date до даты оплаты; если не оплачено — по текущую дату.
