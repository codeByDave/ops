-- Roadside Responder Ops import SQL with participant links
-- Generated from uploaded workbook ops_import_templates_v2.xlsx
-- Run locally first only.
-- If you want to test without saving, change COMMIT; at the bottom to ROLLBACK;

START TRANSACTION;
SET @company_id := (SELECT id FROM companies ORDER BY id LIMIT 1);

-- Motor club account
INSERT INTO customers (company_id, public_id, customer_type_id, first_name, last_name, company_name, email, mobile_phone, address_1, address_2, city, state, postal_code, notes, created_at, updated_at)
SELECT @company_id, 'CUST-990000', (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'customer_type' AND lv.code = 'motor_club' LIMIT 1), NULL, NULL, 'Agero', NULL, NULL, '400 Rivers Edge Drive', NULL, 'Medford', 'MA', '02155', 'Imported motor club account', NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM customers WHERE company_id=@company_id AND company_name='Agero');

-- Customers
-- Alexander Ramos
INSERT INTO customers (company_id, public_id, customer_type_id, first_name, last_name, company_name, email, mobile_phone, address_1, address_2, city, state, postal_code, notes, created_at, updated_at)
SELECT @company_id, 'CUST-990001', (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'customer_type' AND lv.code = 'consumer' LIMIT 1), 'Alexander', 'Ramos', NULL, 'rubimontro@gmail.com', '9042386732', '573 Oakleaf Plantation Pkwy', NULL, 'Orange Park', 'FL', '32065', NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM customers WHERE company_id=@company_id AND mobile_phone='9042386732');
-- Jahkheme Scott
INSERT INTO customers (company_id, public_id, customer_type_id, first_name, last_name, company_name, email, mobile_phone, address_1, address_2, city, state, postal_code, notes, created_at, updated_at)
SELECT @company_id, 'CUST-990002', (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'customer_type' AND lv.code = 'consumer' LIMIT 1), 'Jahkheme', 'Scott', NULL, 'biked278@gmail.com', '9049386281', NULL, NULL, NULL, NULL, NULL, NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM customers WHERE company_id=@company_id AND mobile_phone='9049386281');
-- Frank Wise
INSERT INTO customers (company_id, public_id, customer_type_id, first_name, last_name, company_name, email, mobile_phone, address_1, address_2, city, state, postal_code, notes, created_at, updated_at)
SELECT @company_id, 'CUST-990003', (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'customer_type' AND lv.code = 'consumer' LIMIT 1), 'Frank', 'Wise', NULL, 'frankwise@me.com', '9045765077', '1898 San Marco Blvd', NULL, 'Jacksonville', 'FL', '32207', NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM customers WHERE company_id=@company_id AND mobile_phone='9045765077');
-- Lonnie Dunnigan
INSERT INTO customers (company_id, public_id, customer_type_id, first_name, last_name, company_name, email, mobile_phone, address_1, address_2, city, state, postal_code, notes, created_at, updated_at)
SELECT @company_id, 'CUST-990004', (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'customer_type' AND lv.code = 'consumer' LIMIT 1), 'Lonnie', 'Dunnigan', NULL, 'lonniedunnigan@gmail.com', '9047634042', '5258 Collins Preserve LaneJacksonville', NULL, NULL, 'FL', '32244', NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM customers WHERE company_id=@company_id AND mobile_phone='9047634042');
-- James Ratcliff
INSERT INTO customers (company_id, public_id, customer_type_id, first_name, last_name, company_name, email, mobile_phone, address_1, address_2, city, state, postal_code, notes, created_at, updated_at)
SELECT @company_id, 'CUST-990005', (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'customer_type' AND lv.code = 'consumer' LIMIT 1), 'James', 'Ratcliff', NULL, 'scouthobby@comcast.net', '3605563914', '40 Narvarez Ave', NULL, 'Saint Augustine', 'FL', '32084', NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM customers WHERE company_id=@company_id AND mobile_phone='3605563914');
-- Curtis Mracek
INSERT INTO customers (company_id, public_id, customer_type_id, first_name, last_name, company_name, email, mobile_phone, address_1, address_2, city, state, postal_code, notes, created_at, updated_at)
SELECT @company_id, 'CUST-990006', (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'customer_type' AND lv.code = 'consumer' LIMIT 1), 'Curtis', 'Mracek', NULL, 'curtispmracek@gmail.com', '5206685586', NULL, NULL, NULL, NULL, NULL, NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM customers WHERE company_id=@company_id AND mobile_phone='5206685586');
-- Thomas Savino
INSERT INTO customers (company_id, public_id, customer_type_id, first_name, last_name, company_name, email, mobile_phone, address_1, address_2, city, state, postal_code, notes, created_at, updated_at)
SELECT @company_id, 'CUST-990007', (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'customer_type' AND lv.code = 'consumer' LIMIT 1), 'Thomas', 'Savino', NULL, 'thsavino@gmail.com', '3213132284', NULL, NULL, NULL, NULL, NULL, NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM customers WHERE company_id=@company_id AND mobile_phone='3213132284');
-- Nicholas Morcom
INSERT INTO customers (company_id, public_id, customer_type_id, first_name, last_name, company_name, email, mobile_phone, address_1, address_2, city, state, postal_code, notes, created_at, updated_at)
SELECT @company_id, 'CUST-990008', (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'customer_type' AND lv.code = 'consumer' LIMIT 1), 'Nicholas', 'Morcom', NULL, 'nickmorcom@yahoo.com', '9044151642', '48 Deer Meadows Dr', NULL, 'Saint Augustine', 'FL', '32092', 'upsell Agero | Imported from Agero-related customer row', NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM customers WHERE company_id=@company_id AND mobile_phone='9044151642');
-- Joseph Gardner
INSERT INTO customers (company_id, public_id, customer_type_id, first_name, last_name, company_name, email, mobile_phone, address_1, address_2, city, state, postal_code, notes, created_at, updated_at)
SELECT @company_id, 'CUST-990009', (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'customer_type' AND lv.code = 'consumer' LIMIT 1), 'Joseph', 'Gardner', NULL, NULL, '8048156139', NULL, NULL, NULL, NULL, NULL, 'Agero | Imported from Agero-related customer row', NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM customers WHERE company_id=@company_id AND mobile_phone='8048156139');
-- Raymond Swift
INSERT INTO customers (company_id, public_id, customer_type_id, first_name, last_name, company_name, email, mobile_phone, address_1, address_2, city, state, postal_code, notes, created_at, updated_at)
SELECT @company_id, 'CUST-990010', (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'customer_type' AND lv.code = 'consumer' LIMIT 1), 'Raymond', 'Swift', NULL, NULL, '3528484944', NULL, NULL, NULL, NULL, NULL, 'Agero | Imported from Agero-related customer row', NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM customers WHERE company_id=@company_id AND mobile_phone='3528484944');
-- Matt Probst
INSERT INTO customers (company_id, public_id, customer_type_id, first_name, last_name, company_name, email, mobile_phone, address_1, address_2, city, state, postal_code, notes, created_at, updated_at)
SELECT @company_id, 'CUST-990011', (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'customer_type' AND lv.code = 'consumer' LIMIT 1), 'Matt', 'Probst', NULL, 'mattpr@gmail.com', '9042172024', NULL, NULL, NULL, NULL, NULL, 'Customer address was Agero HQ in source sheet, left blank.', NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM customers WHERE company_id=@company_id AND mobile_phone='9042172024');

-- Customer vehicles
-- Alexander Ramos vehicle
INSERT INTO vehicles (company_id, customer_id, year, make, model, color, vin, tag_state, tag_number, notes, created_at, updated_at)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9042386732' LIMIT 1), 2003, 'Cadilliac', 'CTS', NULL, NULL, 'FL', 'FQAQ03', NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM vehicles WHERE company_id=@company_id AND customer_id=(SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9042386732' LIMIT 1) AND tag_number='FQAQ03');
-- Jahkheme Scott vehicle
INSERT INTO vehicles (company_id, customer_id, year, make, model, color, vin, tag_state, tag_number, notes, created_at, updated_at)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9049386281' LIMIT 1), 2008, 'Camry', 'SE', NULL, NULL, 'SC', '437CCK', NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM vehicles WHERE company_id=@company_id AND customer_id=(SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9049386281' LIMIT 1) AND tag_number='437CCK');
-- Frank Wise vehicle
INSERT INTO vehicles (company_id, customer_id, year, make, model, color, vin, tag_state, tag_number, notes, created_at, updated_at)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9045765077' LIMIT 1), 2018, 'Audi', 'Q3', NULL, NULL, 'FL', 'JWYB06', NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM vehicles WHERE company_id=@company_id AND customer_id=(SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9045765077' LIMIT 1) AND tag_number='JWYB06');
-- Lonnie Dunnigan vehicle
INSERT INTO vehicles (company_id, customer_id, year, make, model, color, vin, tag_state, tag_number, notes, created_at, updated_at)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9047634042' LIMIT 1), 2006, 'Honda', 'Accord', NULL, NULL, 'FL', 'FQAR96', NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM vehicles WHERE company_id=@company_id AND customer_id=(SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9047634042' LIMIT 1) AND tag_number='FQAR96');
-- James Ratcliff vehicle
INSERT INTO vehicles (company_id, customer_id, year, make, model, color, vin, tag_state, tag_number, notes, created_at, updated_at)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '3605563914' LIMIT 1), 2018, 'Lincoln', 'Continental', NULL, NULL, NULL, NULL, NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM vehicles WHERE company_id=@company_id AND customer_id=(SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '3605563914' LIMIT 1) AND year=2018 AND make='Lincoln' AND model='Continental');
-- Curtis Mracek vehicle
INSERT INTO vehicles (company_id, customer_id, year, make, model, color, vin, tag_state, tag_number, notes, created_at, updated_at)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '5206685586' LIMIT 1), 2009, 'Mercedes', 'SI550', NULL, NULL, NULL, NULL, NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM vehicles WHERE company_id=@company_id AND customer_id=(SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '5206685586' LIMIT 1) AND year=2009 AND make='Mercedes' AND model='SI550');
-- Thomas Savino vehicle
INSERT INTO vehicles (company_id, customer_id, year, make, model, color, vin, tag_state, tag_number, notes, created_at, updated_at)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '3213132284' LIMIT 1), 2021, 'BMW', NULL, NULL, NULL, 'FL', 'EB22CH', NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM vehicles WHERE company_id=@company_id AND customer_id=(SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '3213132284' LIMIT 1) AND tag_number='EB22CH');
-- Nicholas Morcom vehicle
INSERT INTO vehicles (company_id, customer_id, year, make, model, color, vin, tag_state, tag_number, notes, created_at, updated_at)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9044151642' LIMIT 1), 2020, 'Lexus', 'ES350', 'Silver', '58ADZ1B13LU064425', 'FL', 'KH073C', NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM vehicles WHERE company_id=@company_id AND customer_id=(SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9044151642' LIMIT 1) AND tag_number='KH073C');
-- Joseph Gardner vehicle
INSERT INTO vehicles (company_id, customer_id, year, make, model, color, vin, tag_state, tag_number, notes, created_at, updated_at)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '8048156139' LIMIT 1), 2024, 'Toyota', 'Tacoma', 'Silver', '3TMLB5JN9RM036150', 'FL', 'FSCJ86', NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM vehicles WHERE company_id=@company_id AND customer_id=(SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '8048156139' LIMIT 1) AND tag_number='FSCJ86');
-- Raymond Swift vehicle
INSERT INTO vehicles (company_id, customer_id, year, make, model, color, vin, tag_state, tag_number, notes, created_at, updated_at)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '3528484944' LIMIT 1), 2016, 'Ford', 'F250', 'White', NULL, NULL, NULL, NULL, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM vehicles WHERE company_id=@company_id AND customer_id=(SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '3528484944' LIMIT 1) AND year=2016 AND make='Ford' AND model='F250');
-- Matt Probst vehicle
INSERT INTO vehicles (company_id, customer_id, year, make, model, color, vin, tag_state, tag_number, notes, created_at, updated_at)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9042172024' LIMIT 1), 2007, 'Chevy', 'Silverado', NULL, NULL, 'FL', '67BLZC', 'Created from service call import', NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM vehicles WHERE company_id=@company_id AND customer_id=(SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9042172024' LIMIT 1) AND tag_number='67BLZC');

-- Company vehicles
-- 2026 Ram 1500
INSERT INTO company_vehicles (company_id, description, plate_number, is_active, created_at, updated_at)
SELECT @company_id, '2026 Ram 1500', 'DD501', 1, NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM company_vehicles WHERE company_id=@company_id AND description='2026 Ram 1500');

-- Service calls
-- Service call row 1: account=Alexander Ramos; service_customer=Alexander Ramos
INSERT INTO service_calls (
    company_id, customer_id, vehicle_id, service_type_id, status_id, po_number,
    customer_name, customer_mobile_phone, address_1, city, state, postal_code,
    latitude, longitude, vehicle_label,
    scheduled_for, dispatched_at, enroute_at, arrived_at, completed_at,
    notes, created_at, updated_at
)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9042386732' LIMIT 1), (SELECT id FROM vehicles WHERE company_id=@company_id AND customer_id = (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9042386732' LIMIT 1) AND tag_number = 'FQAQ03' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_type' AND lv.code = 'jump_start' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_call_status' AND lv.code = 'completed' LIMIT 1), NULL, 'Alexander Ramos', '9042386732', 'I-10 Exit 356', 'Orange Park', 'FL', '32065', NULL, NULL, '2003 Cadilliac CTS', NULL, '2026-04-15 03:51:00', '2026-04-15 03:51:00', '2026-04-15 03:51:00', '2026-04-15 03:51:00', 'Battery tested okay. Vehicle cranking w/o jump. Only charged dispatch fee. Cust needs tow.', '2026-04-15 03:51:00', NOW();
-- Service call row 2: account=Jahkheme Scott; service_customer=Jahkheme Scott
INSERT INTO service_calls (
    company_id, customer_id, vehicle_id, service_type_id, status_id, po_number,
    customer_name, customer_mobile_phone, address_1, city, state, postal_code,
    latitude, longitude, vehicle_label,
    scheduled_for, dispatched_at, enroute_at, arrived_at, completed_at,
    notes, created_at, updated_at
)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9049386281' LIMIT 1), (SELECT id FROM vehicles WHERE company_id=@company_id AND customer_id = (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9049386281' LIMIT 1) AND tag_number = '437CCK' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_type' AND lv.code = 'battery_replacement' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_call_status' AND lv.code = 'completed' LIMIT 1), NULL, 'Jahkheme Scott', '9049386281', '12585 Flagger Center Blvd', 'Jacksonville', 'FL', '32258', NULL, NULL, '2008 Camry SE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NOW();
-- Service call row 3: account=Frank Wise; service_customer=Frank Wise
INSERT INTO service_calls (
    company_id, customer_id, vehicle_id, service_type_id, status_id, po_number,
    customer_name, customer_mobile_phone, address_1, city, state, postal_code,
    latitude, longitude, vehicle_label,
    scheduled_for, dispatched_at, enroute_at, arrived_at, completed_at,
    notes, created_at, updated_at
)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9045765077' LIMIT 1), (SELECT id FROM vehicles WHERE company_id=@company_id AND customer_id = (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9045765077' LIMIT 1) AND tag_number = 'JWYB06' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_type' AND lv.code = 'flat_tire' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_call_status' AND lv.code = 'completed' LIMIT 1), NULL, 'Frank Wise', '9045765077', '1898 San Marco Blvd', 'Jacksonville', 'FL', '32207', NULL, NULL, '2018 Audi Q3', NULL, '2026-04-21 09:37:00', '2026-04-21 09:37:00', '2026-04-21 09:37:00', '2026-04-21 09:37:00', 'Plugged tire at cust request. Advised customer to get tire repaired asap.', '2026-04-21 09:37:00', NOW();
-- Service call row 4: account=Lonnie Dunnigan; service_customer=Lonnie Dunnigan
INSERT INTO service_calls (
    company_id, customer_id, vehicle_id, service_type_id, status_id, po_number,
    customer_name, customer_mobile_phone, address_1, city, state, postal_code,
    latitude, longitude, vehicle_label,
    scheduled_for, dispatched_at, enroute_at, arrived_at, completed_at,
    notes, created_at, updated_at
)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9047634042' LIMIT 1), (SELECT id FROM vehicles WHERE company_id=@company_id AND customer_id = (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9047634042' LIMIT 1) AND tag_number = 'FQAR96' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_type' AND lv.code = 'flat_tire' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_call_status' AND lv.code = 'completed' LIMIT 1), NULL, 'Lonnie Dunnigan', '9047634042', '5258 Collins Preserve Lane', 'Jacksonville', 'FL', '32244', NULL, NULL, '2006 Honda Accord', NULL, '2026-04-25 09:40:00', '2026-04-25 09:40:00', '2026-04-25 09:40:00', '2026-04-25 09:40:00', NULL, '2026-04-25 09:40:00', NOW();
-- Service call row 5: account=James Ratcliff; service_customer=James Ratcliff
INSERT INTO service_calls (
    company_id, customer_id, vehicle_id, service_type_id, status_id, po_number,
    customer_name, customer_mobile_phone, address_1, city, state, postal_code,
    latitude, longitude, vehicle_label,
    scheduled_for, dispatched_at, enroute_at, arrived_at, completed_at,
    notes, created_at, updated_at
)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '3605563914' LIMIT 1), (SELECT id FROM vehicles WHERE company_id=@company_id AND customer_id = (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '3605563914' LIMIT 1) AND year = 2018 AND make = 'Lincoln' AND model = 'Continental' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_type' AND lv.code = 'jump_start' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_call_status' AND lv.code = 'completed' LIMIT 1), NULL, 'James Ratcliff', '3605563914', '40 Narvarez Ave', 'Saint Augustine', 'FL', '32084', NULL, NULL, '2018 Lincoln Continental', '2026-04-27 09:30:00', '2026-04-27 09:30:00', '2026-04-27 09:30:00', '2026-04-27 09:30:00', '2026-04-27 09:30:00', 'Disconnect battery. Cust will be gone for 6 months and would like for us to return to reconnect the battery.', '2026-04-27 09:30:00', NOW();
-- Service call row 6: account=Curtis Mracek; service_customer=Curtis Mracek
INSERT INTO service_calls (
    company_id, customer_id, vehicle_id, service_type_id, status_id, po_number,
    customer_name, customer_mobile_phone, address_1, city, state, postal_code,
    latitude, longitude, vehicle_label,
    scheduled_for, dispatched_at, enroute_at, arrived_at, completed_at,
    notes, created_at, updated_at
)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '5206685586' LIMIT 1), (SELECT id FROM vehicles WHERE company_id=@company_id AND customer_id = (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '5206685586' LIMIT 1) AND year = 2009 AND make = 'Mercedes' AND model = 'SI550' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_type' AND lv.code = 'flat_tire' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_call_status' AND lv.code = 'completed' LIMIT 1), NULL, 'Curtis Mracek', '5206685586', 'Near Saw Grass North Gate', 'Ponte Vedra Beach', 'FL', '32082', NULL, NULL, '2009 Mercedes Sl550', NULL, '2026-04-27 14:15:00', '2026-04-27 14:15:00', '2026-04-27 14:15:00', '2026-04-27 14:15:00', 'Tire was dry rotted on inner and outter wheel. Spare tire was unusable. Gave cust ride home and wife will take him to get a new tire.', '2026-04-27 14:15:00', NOW();
-- Service call row 7: account=Thomas Savino; service_customer=Thomas Savino
INSERT INTO service_calls (
    company_id, customer_id, vehicle_id, service_type_id, status_id, po_number,
    customer_name, customer_mobile_phone, address_1, city, state, postal_code,
    latitude, longitude, vehicle_label,
    scheduled_for, dispatched_at, enroute_at, arrived_at, completed_at,
    notes, created_at, updated_at
)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '3213132284' LIMIT 1), (SELECT id FROM vehicles WHERE company_id=@company_id AND customer_id = (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '3213132284' LIMIT 1) AND tag_number = 'EB22CH' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_type' AND lv.code = 'flat_tire' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_call_status' AND lv.code = 'completed' LIMIT 1), NULL, 'Thomas Savino', '3213132284', '2904 Marmaris Dr', 'Jacksonville', 'FL', '32246', NULL, NULL, '2021 BMW', NULL, '2026-04-27 22:01:00', '2026-04-27 22:01:00', '2026-04-27 22:01:00', '2026-04-27 22:01:00', '3-4" hole on side wall of tire. No spare, advised they will need a tow.', '2026-04-27 22:01:00', NOW();
-- Service call row 8: account=Agero; service_customer=Nicholas Morcom
INSERT INTO service_calls (
    company_id, customer_id, vehicle_id, service_type_id, status_id, po_number,
    customer_name, customer_mobile_phone, address_1, city, state, postal_code,
    latitude, longitude, vehicle_label,
    scheduled_for, dispatched_at, enroute_at, arrived_at, completed_at,
    notes, created_at, updated_at
)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND company_name = 'Agero' LIMIT 1), (SELECT id FROM vehicles WHERE company_id=@company_id AND customer_id = (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9044151642' LIMIT 1) AND vin = '58ADZ1B13LU064425' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_type' AND lv.code = 'jump_start' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_call_status' AND lv.code = 'completed' LIMIT 1), NULL, 'Nicholas Morcom', '9044151642', '48 Deer Meadows Dr', 'Saint Augustine', 'FL', '32092', NULL, NULL, '2020 Lexus ES 350', NULL, '2026-04-29 07:43:00', '2026-04-29 07:43:00', '2026-04-29 07:43:00', '2026-04-29 07:43:00', 'Battery test failed. Cust opted for us to install new battery.', '2026-04-29 07:43:00', NOW();
-- Service call row 9: account=Agero; service_customer=Joseph Gardner
INSERT INTO service_calls (
    company_id, customer_id, vehicle_id, service_type_id, status_id, po_number,
    customer_name, customer_mobile_phone, address_1, city, state, postal_code,
    latitude, longitude, vehicle_label,
    scheduled_for, dispatched_at, enroute_at, arrived_at, completed_at,
    notes, created_at, updated_at
)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND company_name = 'Agero' LIMIT 1), (SELECT id FROM vehicles WHERE company_id=@company_id AND customer_id = (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '8048156139' LIMIT 1) AND vin = '3TMLB5JN9RM036150' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_type' AND lv.code = 'flat_tire' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_call_status' AND lv.code = 'completed' LIMIT 1), NULL, 'Joseph Gardner', '8048156139', 'I-95 N Exit 333', 'Jacksonville', 'FL', '32258', NULL, NULL, '2024 Toyota Tacoma - Silver', NULL, '2026-04-21 07:09:00', '2026-04-21 07:09:00', '2026-04-21 07:09:00', '2026-04-21 07:09:00', NULL, '2026-04-21 07:09:00', NOW();
-- Service call row 10: account=Agero; service_customer=Raymond Swift
INSERT INTO service_calls (
    company_id, customer_id, vehicle_id, service_type_id, status_id, po_number,
    customer_name, customer_mobile_phone, address_1, city, state, postal_code,
    latitude, longitude, vehicle_label,
    scheduled_for, dispatched_at, enroute_at, arrived_at, completed_at,
    notes, created_at, updated_at
)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND company_name = 'Agero' LIMIT 1), (SELECT id FROM vehicles WHERE company_id=@company_id AND customer_id = (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '3528484944' LIMIT 1) AND year = 2016 AND make = 'Ford' AND model = 'F250' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_type' AND lv.code = 'jump_start' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_call_status' AND lv.code = 'cancelled' LIMIT 1), NULL, 'Raymond Swift', '3528484944', '4903 Coquina Crossing Drive', 'Elkton', 'FL', '32033', 29.8058299, -81.3905156, '2016 Ford F250', NULL, '2026-04-25 15:57:00', '2026-04-25 15:57:00', '2026-04-25 15:57:00', '2026-04-25 15:57:00', 'Agero reassigned called while enroute.', '2026-04-25 15:57:00', NOW();
-- Service call row 11: account=Matt Probst; service_customer=Matt Probst
INSERT INTO service_calls (
    company_id, customer_id, vehicle_id, service_type_id, status_id, po_number,
    customer_name, customer_mobile_phone, address_1, city, state, postal_code,
    latitude, longitude, vehicle_label,
    scheduled_for, dispatched_at, enroute_at, arrived_at, completed_at,
    notes, created_at, updated_at
)
SELECT @company_id, (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9042172024' LIMIT 1), (SELECT id FROM vehicles WHERE company_id=@company_id AND customer_id = (SELECT id FROM customers WHERE company_id = @company_id AND mobile_phone = '9042172024' LIMIT 1) AND tag_number = '67BLZC' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_type' AND lv.code = 'flat_tire' LIMIT 1), (SELECT lv.id FROM lookup_values lv JOIN lookup_types lt ON lt.id = lv.lookup_type_id WHERE lt.code = 'service_call_status' AND lv.code = 'completed' LIMIT 1), NULL, 'Matt Probst', '9042172024', 'S of Target', 'Ponte Vedra Beach', 'FL', '32082', NULL, NULL, '2007 Chevy Silverado', NULL, '2026-04-30 17:00:00', '2026-04-30 17:00:00', '2026-04-30 17:00:00', '2026-04-30 17:00:00', 'Called for flat tire, and they were unable to lower spare. Vehicle battery died while waiting. Also performed jump start. Battery tested bad, but we do not carry their battery size.', '2026-04-30 17:00:00', NOW();

-- Assign imported service calls to Dave Kimble and assigned truck DD501.
UPDATE service_calls
SET
    assigned_user_id = (
        SELECT id
        FROM users
        WHERE first_name = 'Dave'
          AND last_name = 'Kimble'
          AND deleted_at IS NULL
        LIMIT 1
    ),
    assigned_company_vehicle_id = (
        SELECT id
        FROM company_vehicles
        WHERE company_id = @company_id
          AND plate_number = 'DD501'
          AND deleted_at IS NULL
        ORDER BY CASE WHEN description = '2026 Ram 1500 Limited' THEN 0 ELSE 1 END, id
        LIMIT 1
    ),
    updated_at = NOW()
WHERE company_id = @company_id;

-- Fix Jahkheme Scott dispatch timeline.
UPDATE service_calls sc
JOIN customers c ON c.id = sc.customer_id
SET
    sc.dispatched_at = '2026-04-16 16:10:00',
    sc.enroute_at = '2026-04-16 16:10:00',
    sc.arrived_at = '2026-04-16 16:10:00',
    sc.updated_at = NOW()
WHERE c.first_name = 'Jahkheme'
  AND c.last_name = 'Scott'
  AND sc.customer_name = 'Jahkheme Scott';

-- Link Agero service calls to the physical service customers.
-- Agero remains the primary account on service_calls.customer_id.
INSERT IGNORE INTO service_call_participants (
    company_id,
    service_call_id,
    customer_id,
    role,
    created_at,
    updated_at
)
SELECT
    sc.company_id,
    sc.id,
    c.id,
    'service_customer',
    NOW(),
    NOW()
FROM service_calls sc
JOIN customers agero ON agero.id = sc.customer_id
JOIN customers c
    ON c.company_id = sc.company_id
   AND CONCAT(c.first_name, ' ', c.last_name) = sc.customer_name
   AND c.mobile_phone = sc.customer_mobile_phone
WHERE agero.company_name = 'Agero'
  AND sc.customer_name IS NOT NULL
  AND sc.customer_mobile_phone IS NOT NULL;

-- Quick counts after import
SELECT 'customers' AS table_name, COUNT(*) AS rows_count FROM customers
UNION ALL SELECT 'vehicles', COUNT(*) FROM vehicles
UNION ALL SELECT 'company_vehicles', COUNT(*) FROM company_vehicles
UNION ALL SELECT 'service_calls', COUNT(*) FROM service_calls;

COMMIT;
