-- ============================================================
-- TT Electro Store — COMPLETE INSERT DATA
-- Safe to run multiple times — uses INSERT IGNORE throughout.
-- Run AFTER schema.sql on a fresh or existing database.
--
-- Tables:  users · categories · brands · banners · products
--          diy_kits · blogs · offers · coupons · faq · reviews
--
-- Default admin: admin@ttelectro.in / Admin@123
--                ↑ CHANGE THIS PASSWORD AFTER FIRST LOGIN
-- ============================================================

SET NAMES utf8mb4;
SET time_zone          = '+05:30';
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE           = '';

-- ============================================================
-- 1. USERS  (4 rows)
-- ============================================================
INSERT IGNORE INTO `users`
  (`id`, `name`, `email`, `password`, `phone`, `role`, `loyalty_points`, `is_active`)
VALUES
  (1, 'Guest',      'guest@ttelectro.in',  '',                                                                           NULL,               'guest', 0,   0),
  (2, 'Admin',      'admin@ttelectro.in',  '$2y$12$Xb/teBJPROG/1ApylHtS/u./DC6KhKmaqRj0dsfZmfbKTlQ5MdeoC', '+91 98765 43210', 'admin', 0,   1),
  (3, 'Ravi Kumar', 'ravi@example.com',    '$2y$12$Xb/teBJPROG/1ApylHtS/u./DC6KhKmaqRj0dsfZmfbKTlQ5MdeoC', '+91 91234 56789', 'user',  150, 1),
  (4, 'Priya Nair', 'priya@example.com',   '$2y$12$Xb/teBJPROG/1ApylHtS/u./DC6KhKmaqRj0dsfZmfbKTlQ5MdeoC', '+91 98001 23456', 'user',  80,  1);


-- ============================================================
-- 2. CATEGORIES  (10 rows)
-- ============================================================
INSERT IGNORE INTO `categories` (`id`, `name`, `slug`, `description`, `icon`)
VALUES
  (1,  'Microcontrollers',    'microcontrollers',   'Development boards — Arduino, ESP32, Raspberry Pi Pico, STM32 and more.',         'fa-microchip'),
  (2,  'Sensors',             'sensors',            'Temperature, humidity, distance, gas, IMU and environmental sensor modules.',     'fa-satellite-dish'),
  (3,  'Displays',            'displays',           'OLED, TFT, LCD and e-paper displays for Arduino and Raspberry Pi projects.',      'fa-display'),
  (4,  'Power Modules',       'power-modules',      'Buck converters, LDO regulators, battery chargers and UPS modules.',             'fa-bolt'),
  (5,  'Communication',       'communication',      'Bluetooth, WiFi, RF, GSM, LoRa and wired serial communication modules.',         'fa-wifi'),
  (6,  'Robotics',            'robotics',           'Motor drivers, servo motors, chassis kits, wheels and robotic components.',      'fa-robot'),
  (7,  '3D Printing',         '3d-printing',        'PLA/PETG/ABS filaments, bed adhesives, nozzles and FDM printer accessories.',    'fa-print'),
  (8,  'Tools',               'tools',              'Multimeters, soldering stations, oscilloscopes and hand tools for makers.',      'fa-screwdriver-wrench'),
  (9,  'Cables & Connectors', 'cables-connectors',  'Jumper wires, USB cables, JST connectors and breadboard accessories.',           'fa-plug'),
  (10, 'Kits',                'kits',               'Complete starter kits, project bundles and learning kits for all skill levels.', 'fa-box-open');


-- ============================================================
-- 3. BRANDS  (6 rows)
-- ============================================================
INSERT IGNORE INTO `brands` (`id`, `name`)
VALUES
  (1, 'Arduino'),
  (2, 'Raspberry Pi'),
  (3, 'ESP32'),
  (4, 'Adafruit'),
  (5, 'STMicroelectronics'),
  (6, 'Generic');


-- ============================================================
-- 4. BANNERS  (3 hero slider banners)
-- ============================================================
INSERT IGNORE INTO `banners` (`id`, `title`, `subtitle`, `badge`, `is_active`, `position`)
VALUES
  (1, 'Power Your Next Idea',
      'Premium electronics for makers and engineers in India',
      'New Arrivals', 1, 1),
  (2, 'DIY Vision Kits',
      'Everything you need to build amazing projects',
      'Featured', 1, 2),
  (3, '3D Printing Service',
      'Upload your design, choose your material — we print & deliver',
      'Get a Quote', 1, 3);


-- ============================================================
-- 5. PRODUCTS  (25 rows — all 10 categories)
-- ============================================================
INSERT IGNORE INTO `products`
  (`name`, `slug`, `description`,
   `price`, `original_price`, `discount`, `stock`,
   `thumbnail`, `images`, `tags`, `specifications`,
   `category_id`, `brand_id`,
   `is_featured`, `is_trending`, `is_best_seller`,
   `is_flash_sale`, `flash_sale_price`, `flash_sale_ends`,
   `is_active`)
VALUES

-- ── MICROCONTROLLERS (cat 1) ─────────────────────────────────
(
  'Arduino Uno R3', 'arduino-uno-r3',
  'The iconic Arduino Uno R3 based on ATmega328P. Ideal for beginners and prototyping. 14 digital I/O pins, 6 PWM outputs, 6 analog inputs, USB-B port.',
  599.00, 799.00, 25.03, 150,
  'https://picsum.photos/seed/arduino-uno-r3/600/600',
  '["https://picsum.photos/seed/arduino-uno-r3/600/600","https://picsum.photos/seed/arduino-uno-r3b/600/600"]',
  '["arduino","microcontroller","beginner","atmega328p","uno"]',
  '[{"key":"Microcontroller","value":"ATmega328P"},{"key":"Operating Voltage","value":"5V"},{"key":"Input Voltage","value":"7–12V"},{"key":"Digital I/O","value":"14 (6 PWM)"},{"key":"Analog Inputs","value":"6"},{"key":"Flash","value":"32 KB"},{"key":"SRAM","value":"2 KB"},{"key":"Clock","value":"16 MHz"}]',
  1, 1, 1, 0, 1, 0, NULL, NULL, 1
),
(
  'Arduino Nano Every', 'arduino-nano-every',
  'Compact Arduino Nano Every based on ATMega4809. More memory and faster than classic Nano. Perfect for space-constrained projects.',
  349.00, 499.00, 30.06, 200,
  'https://picsum.photos/seed/arduino-nano-every/600/600',
  '["https://picsum.photos/seed/arduino-nano-every/600/600"]',
  '["arduino","nano","compact","atmega4809","breadboard"]',
  '[{"key":"Microcontroller","value":"ATMega4809"},{"key":"Operating Voltage","value":"5V"},{"key":"Digital I/O","value":"14"},{"key":"Analog Inputs","value":"8"},{"key":"Flash","value":"48 KB"},{"key":"SRAM","value":"6 KB"},{"key":"Clock","value":"20 MHz"}]',
  1, 1, 0, 1, 0, 0, NULL, NULL, 1
),
(
  'ESP32 DevKit V1', 'esp32-devkit-v1',
  'Feature-rich WiFi + Bluetooth development board. Dual-core 240 MHz Xtensa LX6, 38 GPIO pins, built-in BLE 4.2. Best value IoT board in 2026.',
  449.00, 599.00, 25.04, 300,
  'https://picsum.photos/seed/esp32-devkit/600/600',
  '["https://picsum.photos/seed/esp32-devkit/600/600","https://picsum.photos/seed/esp32-devkit-b/600/600"]',
  '["esp32","wifi","bluetooth","iot","dual-core"]',
  '[{"key":"Processor","value":"Xtensa Dual-Core 32-bit LX6"},{"key":"Clock","value":"240 MHz"},{"key":"Flash","value":"4 MB"},{"key":"SRAM","value":"520 KB"},{"key":"WiFi","value":"802.11 b/g/n 2.4 GHz"},{"key":"Bluetooth","value":"BLE 4.2 + Classic"},{"key":"GPIO","value":"38"},{"key":"Voltage","value":"3.3V"}]',
  1, 3, 1, 1, 0, 0, NULL, NULL, 1
),
(
  'Raspberry Pi Pico W', 'raspberry-pi-pico-w',
  'RP2040-based Pico with on-board WiFi 802.11n. Programme in MicroPython or C/C++. Dual-core ARM Cortex-M0+ at 133 MHz, 264 KB SRAM, 26 GPIO.',
  749.00, 999.00, 25.02, 120,
  'https://picsum.photos/seed/rpi-pico-w/600/600',
  '["https://picsum.photos/seed/rpi-pico-w/600/600"]',
  '["raspberry-pi","pico","micropython","rp2040","wifi"]',
  '[{"key":"Processor","value":"RP2040 Dual-Core Cortex-M0+"},{"key":"Clock","value":"133 MHz"},{"key":"SRAM","value":"264 KB"},{"key":"Flash","value":"2 MB"},{"key":"WiFi","value":"802.11n 2.4 GHz"},{"key":"GPIO","value":"26"},{"key":"ADC","value":"3× 12-bit"}]',
  1, 2, 1, 1, 0, 0, NULL, NULL, 1
),

-- ── SENSORS (cat 2) ──────────────────────────────────────────
(
  'DHT22 Temperature & Humidity Sensor', 'dht22-temp-humidity-sensor',
  'Calibrated digital temperature and humidity sensor. Wider range and higher accuracy than DHT11. Single-wire interface, compatible with Arduino, ESP32, and Raspberry Pi.',
  199.00, 299.00, 33.44, 500,
  'https://picsum.photos/seed/dht22-sensor/600/600',
  '["https://picsum.photos/seed/dht22-sensor/600/600"]',
  '["sensor","temperature","humidity","dht22","iot","climate"]',
  '[{"key":"Temp Range","value":"-40°C to +80°C"},{"key":"Temp Accuracy","value":"±0.5°C"},{"key":"Humidity","value":"0–100% RH"},{"key":"Humidity Accuracy","value":"±2–5% RH"},{"key":"Sample Rate","value":"0.5 Hz"},{"key":"Voltage","value":"3.3–6V"},{"key":"Interface","value":"Single-wire"}]',
  2, 6, 0, 1, 0, 1, 149.00, '2026-08-31 23:59:59', 1
),
(
  'HC-SR04 Ultrasonic Distance Sensor', 'hc-sr04-ultrasonic-sensor',
  'Measures distance 2 cm to 400 cm using ultrasonic pulses. 3 mm accuracy, 15° measuring angle. Used in robotics, obstacle detection, and level sensing.',
  99.00, 149.00, 33.56, 800,
  'https://picsum.photos/seed/hcsr04-sensor/600/600',
  '["https://picsum.photos/seed/hcsr04-sensor/600/600"]',
  '["sensor","ultrasonic","distance","hc-sr04","robotics"]',
  '[{"key":"Range","value":"2 cm – 400 cm"},{"key":"Accuracy","value":"3 mm"},{"key":"Angle","value":"15°"},{"key":"Voltage","value":"5V DC"},{"key":"Current","value":"15 mA"},{"key":"Trigger","value":"10µs TTL pulse"}]',
  2, 6, 0, 0, 1, 0, NULL, NULL, 1
),
(
  'MPU-6050 6-Axis Accelerometer & Gyroscope', 'mpu6050-accelerometer-gyroscope',
  'Combined 3-axis accelerometer + 3-axis gyroscope on one chip. I2C interface, 16-bit ADC, built-in DMP. Used in drones, balancing robots, and wearables.',
  149.00, 249.00, 40.16, 600,
  'https://picsum.photos/seed/mpu6050/600/600',
  '["https://picsum.photos/seed/mpu6050/600/600"]',
  '["sensor","imu","gyroscope","accelerometer","mpu6050","drone"]',
  '[{"key":"Accel Range","value":"±2g/4g/8g/16g"},{"key":"Gyro Range","value":"±250/500/1000/2000 °/s"},{"key":"Interface","value":"I2C (400kHz)"},{"key":"Voltage","value":"3.3–5V"},{"key":"ADC","value":"16-bit"},{"key":"Extra","value":"DMP + Temperature sensor"}]',
  2, 6, 0, 0, 0, 1, 99.00, '2026-08-31 23:59:59', 1
),
(
  'MQ-135 Air Quality Gas Sensor', 'mq135-air-quality-sensor',
  'Detects NH3, NOx, alcohol, benzene, smoke, and CO2. Analog + digital output. Widely used in air quality monitors, ventilation systems, and smart home projects.',
  129.00, 199.00, 35.17, 400,
  'https://picsum.photos/seed/mq135-sensor/600/600',
  '["https://picsum.photos/seed/mq135-sensor/600/600"]',
  '["sensor","gas","air-quality","mq135","smoke","co2"]',
  '[{"key":"Detects","value":"NH3, NOx, Alcohol, CO2, Benzene"},{"key":"Output","value":"Analog + Digital TTL"},{"key":"Heater","value":"5V AC/DC"},{"key":"Voltage","value":"5V"},{"key":"Preheat","value":"20 seconds"}]',
  2, 6, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── DISPLAYS (cat 3) ─────────────────────────────────────────
(
  '0.96" OLED Display I2C (SSD1306)', 'oled-096-display-i2c',
  'Crisp 0.96" OLED using SSD1306 driver over I2C. No backlight — emissive pixels give excellent contrast. Just 2 wires (SDA+SCL). Works with all Arduino, ESP32 and STM32 boards.',
  249.00, 349.00, 28.65, 350,
  'https://picsum.photos/seed/oled-display-ssd1306/600/600',
  '["https://picsum.photos/seed/oled-display-ssd1306/600/600"]',
  '["display","oled","ssd1306","i2c","128x64","screen"]',
  '[{"key":"Size","value":"0.96 inch"},{"key":"Resolution","value":"128×64 px"},{"key":"Driver","value":"SSD1306"},{"key":"Interface","value":"I2C (0x3C)"},{"key":"Voltage","value":"3.3–5V"},{"key":"Colour","value":"Monochrome"},{"key":"View Angle","value":"160°"}]',
  3, 6, 1, 0, 1, 0, NULL, NULL, 1
),
(
  '16x2 LCD Display with I2C Backpack', '16x2-lcd-i2c-display',
  '16×2 LCD with soldered PCF8574 I2C backpack — only 2 wires needed. Blue backlight, contrast pot. Plug-and-play with LiquidCrystal_I2C library.',
  149.00, 229.00, 34.93, 400,
  'https://picsum.photos/seed/lcd-16x2-i2c/600/600',
  '["https://picsum.photos/seed/lcd-16x2-i2c/600/600"]',
  '["display","lcd","16x2","i2c","backlight","pcf8574"]',
  '[{"key":"Size","value":"16 × 2"},{"key":"Backlight","value":"Blue LED"},{"key":"Interface","value":"I2C via PCF8574"},{"key":"I2C Address","value":"0x27"},{"key":"Voltage","value":"5V"},{"key":"Contrast","value":"Adjustable pot"}]',
  3, 6, 0, 0, 1, 0, NULL, NULL, 1
),
(
  '2.4" TFT LCD Touchscreen Shield (ILI9341)', '2-4-tft-lcd-touchscreen-ili9341',
  'Full-colour 2.4" TFT with resistive touchscreen and SD card slot. ILI9341 SPI driver. Plugs on top of Arduino Uno/Mega. 65,536 colours at 240×320 px.',
  499.00, 699.00, 28.61, 180,
  'https://picsum.photos/seed/tft-240x320/600/600',
  '["https://picsum.photos/seed/tft-240x320/600/600"]',
  '["display","tft","touchscreen","ili9341","spi","colour"]',
  '[{"key":"Size","value":"2.4 inch"},{"key":"Resolution","value":"240×320 px"},{"key":"Driver","value":"ILI9341"},{"key":"Interface","value":"SPI"},{"key":"Colours","value":"65,536 (16-bit)"},{"key":"Touch","value":"Resistive XPT2046"},{"key":"SD Card","value":"Yes"}]',
  3, 4, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── POWER MODULES (cat 4) ────────────────────────────────────
(
  'LM2596 DC-DC Buck Converter Module', 'lm2596-buck-converter',
  'Adjustable step-down buck converter. Input 4–40V, output 1.25–37V. Up to 3A. On-board LED voltmeter. 92% efficiency. Ideal for powering 3.3V/5V circuits.',
  119.00, 189.00, 37.03, 600,
  'https://picsum.photos/seed/lm2596-buck/600/600',
  '["https://picsum.photos/seed/lm2596-buck/600/600"]',
  '["power","buck-converter","lm2596","step-down","dc-dc"]',
  '[{"key":"Input","value":"4–40V DC"},{"key":"Output","value":"1.25–37V adjustable"},{"key":"Max Current","value":"3A"},{"key":"Efficiency","value":"Up to 92%"},{"key":"Frequency","value":"150 kHz"},{"key":"Display","value":"LED voltmeter"}]',
  4, 6, 0, 1, 0, 0, NULL, NULL, 1
),
(
  'TP4056 Li-Ion Battery Charger Module (USB-C)', 'tp4056-battery-charger-usb-c',
  'Single-cell Li-Ion/LiPo charger + protection via USB-C. Charges up to 1A CC/CV. Built-in overcharge, over-discharge and short-circuit protection. Dual-colour LED indicator.',
  59.00, 99.00, 40.40, 1000,
  'https://picsum.photos/seed/tp4056-charger/600/600',
  '["https://picsum.photos/seed/tp4056-charger/600/600"]',
  '["power","battery-charger","tp4056","lipo","lithium","usb-c"]',
  '[{"key":"Input","value":"4.5–5.5V USB-C"},{"key":"Charge","value":"Up to 1A"},{"key":"Voltage","value":"4.2V ±1%"},{"key":"Protection","value":"Overcharge + Overdischarge + Short"},{"key":"LED","value":"Red=charging, Blue=full"},{"key":"Cell","value":"3.7V Li-Ion / LiPo"}]',
  4, 6, 0, 0, 1, 0, NULL, NULL, 1
),

-- ── COMMUNICATION (cat 5) ────────────────────────────────────
(
  'HC-05 Bluetooth Serial Module', 'hc05-bluetooth-module',
  'Bluetooth 2.0 SPP module with AT command support. Master and slave modes. Range up to 10 m. Pairs easily with smartphones and PCs. Configurable baud rate.',
  299.00, 449.00, 33.40, 250,
  'https://picsum.photos/seed/hc05-bluetooth/600/600',
  '["https://picsum.photos/seed/hc05-bluetooth/600/600"]',
  '["bluetooth","serial","hc-05","wireless","uart","at-commands"]',
  '[{"key":"Version","value":"Bluetooth 2.0 SPP"},{"key":"Frequency","value":"2.4 GHz ISM"},{"key":"Range","value":"Up to 10 m"},{"key":"Voltage","value":"3.3V (5V tolerant)"},{"key":"Interface","value":"UART TX/RX"},{"key":"Baud","value":"9600 bps default"},{"key":"Mode","value":"Master / Slave"}]',
  5, 6, 0, 0, 1, 0, NULL, NULL, 1
),
(
  'NRF24L01+ 2.4GHz RF Transceiver Module', 'nrf24l01-rf-transceiver',
  'Single-chip 2.4 GHz wireless transceiver with SPI interface. 1 Mbps / 2 Mbps data rates. Up to 100 m range. Low power — ideal for wireless sensor networks.',
  199.00, 299.00, 33.44, 400,
  'https://picsum.photos/seed/nrf24l01-rf/600/600',
  '["https://picsum.photos/seed/nrf24l01-rf/600/600"]',
  '["rf","wireless","nrf24l01","2.4ghz","spi","transceiver"]',
  '[{"key":"Frequency","value":"2.4–2.5 GHz ISM"},{"key":"Data Rate","value":"250k / 1M / 2M bps"},{"key":"Range","value":"Up to 100 m"},{"key":"Voltage","value":"1.9–3.6V"},{"key":"Interface","value":"SPI 10 Mbps"},{"key":"Channels","value":"126"}]',
  5, 6, 0, 0, 0, 1, 149.00, '2026-08-31 23:59:59', 1
),
(
  'SIM800L GSM GPRS Module', 'sim800l-gsm-gprs-module',
  'Quad-band GSM/GPRS module for SMS, voice calls, and GPRS data. AT command control. Includes SIM slot. Perfect for IoT projects needing cellular connectivity.',
  599.00, 849.00, 29.44, 100,
  'https://picsum.photos/seed/sim800l-gsm/600/600',
  '["https://picsum.photos/seed/sim800l-gsm/600/600"]',
  '["gsm","gprs","sim800l","cellular","iot","sms"]',
  '[{"key":"Bands","value":"850/900/1800/1900 MHz"},{"key":"GPRS","value":"Class 10 (85.6 kbps)"},{"key":"SMS","value":"MT/MO/CB/Text/PDU"},{"key":"Voice","value":"Yes"},{"key":"SIM","value":"1.8V / 3V"},{"key":"Voltage","value":"3.4–4.4V"}]',
  5, 6, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── ROBOTICS (cat 6) ─────────────────────────────────────────
(
  'L298N Dual H-Bridge Motor Driver', 'l298n-motor-driver',
  'Controls 2 DC motors or 1 stepper. 2A per channel, 46V supply. Built-in 5V regulator powers your board. Logic-level inputs compatible with Arduino and ESP32.',
  179.00, 269.00, 33.46, 450,
  'https://picsum.photos/seed/l298n-motor-driver/600/600',
  '["https://picsum.photos/seed/l298n-motor-driver/600/600"]',
  '["motor-driver","l298n","dc-motor","stepper","robotics","h-bridge"]',
  '[{"key":"Channels","value":"2 DC or 1 stepper"},{"key":"Max Voltage","value":"46V"},{"key":"Max Current","value":"2A/channel"},{"key":"Peak","value":"3A"},{"key":"Logic","value":"2.5–5V TTL"},{"key":"On-board","value":"5V reg + LED + heat sink"}]',
  6, 6, 0, 1, 1, 0, NULL, NULL, 1
),
(
  'SG90 Micro Servo Motor 9g', 'sg90-micro-servo-motor',
  '9g micro servo with 180° rotation and plastic gears. Includes 3 horns and 2 mounting screws. PWM control (50 Hz, 1–2 ms). Works with Arduino, ESP32, and Raspberry Pi.',
  149.00, 229.00, 34.93, 700,
  'https://picsum.photos/seed/sg90-servo/600/600',
  '["https://picsum.photos/seed/sg90-servo/600/600"]',
  '["servo","motor","sg90","pwm","robotics","rc"]',
  '[{"key":"Weight","value":"9 g"},{"key":"Torque","value":"1.8 kg·cm at 4.8V"},{"key":"Speed","value":"0.1 s/60°"},{"key":"Rotation","value":"0–180°"},{"key":"Control","value":"PWM 50 Hz"},{"key":"Voltage","value":"4.8–6V"}]',
  6, 6, 0, 0, 1, 0, NULL, NULL, 1
),
(
  '2WD Robot Car Chassis Kit', '2wd-robot-car-chassis',
  'Complete 2WD robot platform — acrylic base, 2 DC gear motors (1:48), 2 wheels, caster wheel, brackets, battery holder and all hardware. Perfect for line-follower and obstacle-avoidance robots.',
  549.00, 799.00, 31.28, 80,
  'https://picsum.photos/seed/robot-car-chassis/600/600',
  '["https://picsum.photos/seed/robot-car-chassis/600/600"]',
  '["robot","chassis","2wd","dc-motor","kit","diy"]',
  '[{"key":"Drive","value":"2WD"},{"key":"Gear Ratio","value":"1:48"},{"key":"Motor","value":"3–6V DC"},{"key":"Speed","value":"~0.5 m/s at 5V"},{"key":"Plate","value":"Acrylic"},{"key":"Wheel","value":"65 mm diameter"}]',
  6, 6, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── 3D PRINTING (cat 7) ──────────────────────────────────────
(
  'PLA Filament 1kg Spool (White, 1.75mm)', 'pla-filament-1kg-white-1-75mm',
  'Premium PLA filament, 1.75 mm ±0.02 mm, 1 kg spool. Low warping, biodegradable. Suitable for Ender 3, Prusa, and Bambu Lab. Consistent colour throughout.',
  799.00, 999.00, 20.02, 60,
  'https://picsum.photos/seed/pla-filament-white/600/600',
  '["https://picsum.photos/seed/pla-filament-white/600/600"]',
  '["3d-printing","pla","filament","1.75mm","fdm","ender3"]',
  '[{"key":"Material","value":"PLA"},{"key":"Diameter","value":"1.75 mm ±0.02 mm"},{"key":"Weight","value":"1 kg"},{"key":"Print Temp","value":"180–220°C"},{"key":"Bed Temp","value":"20–60°C (optional)"},{"key":"Colour","value":"White"}]',
  7, 6, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── TOOLS (cat 8) ────────────────────────────────────────────
(
  'Digital Multimeter DT9208A', 'digital-multimeter-dt9208a',
  'Professional 3.5-digit LCD multimeter with auto-range and data hold. Measures DC/AC voltage, current, resistance, capacitance, frequency, temperature (K-type), hFE and diode.',
  899.00, 1299.00, 30.79, 75,
  'https://picsum.photos/seed/multimeter-dt9208a/600/600',
  '["https://picsum.photos/seed/multimeter-dt9208a/600/600"]',
  '["multimeter","tools","voltage","current","resistance","measurement"]',
  '[{"key":"Display","value":"3.5-digit LCD"},{"key":"DC Voltage","value":"200mV–1000V"},{"key":"AC Voltage","value":"200V–750V"},{"key":"DC Current","value":"2mA–10A"},{"key":"Resistance","value":"200Ω–200MΩ"},{"key":"Temperature","value":"-40°C to +1000°C"}]',
  8, 6, 1, 0, 0, 0, NULL, NULL, 1
),
(
  '60W Adjustable Soldering Iron Station', '60w-soldering-iron-station',
  '60W temperature-controlled soldering station with LCD. Range 200–480°C, warm-up ~30 s. Includes iron holder, brass-wool cleaner, 5 replacement tips, solder, and tip-tinner.',
  699.00, 999.00, 30.03, 50,
  'https://picsum.photos/seed/soldering-iron-station/600/600',
  '["https://picsum.photos/seed/soldering-iron-station/600/600"]',
  '["soldering","tools","iron","station","smd","electronics"]',
  '[{"key":"Power","value":"60W"},{"key":"Temp Range","value":"200–480°C"},{"key":"Display","value":"LCD"},{"key":"Warm-up","value":"~30 s to 300°C"},{"key":"Tip","value":"900M series"},{"key":"Includes","value":"5 tips, holder, cleaner, solder"}]',
  8, 6, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── CABLES & CONNECTORS (cat 9) ──────────────────────────────
(
  'Jumper Wire Kit — 120 Pieces (M-M/M-F/F-F)', 'jumper-wire-kit-120pcs',
  '40 M-M + 40 M-F + 40 F-F jumper wires, 20 cm each. Colour-coded, breadboard-friendly, 26 AWG flexible. Essential for prototyping with Arduino, ESP32, and Raspberry Pi.',
  149.00, 199.00, 25.12, 2000,
  'https://picsum.photos/seed/jumper-wires-kit/600/600',
  '["https://picsum.photos/seed/jumper-wires-kit/600/600"]',
  '["jumper-wires","breadboard","connector","prototyping","kit"]',
  '[{"key":"Total","value":"120 pcs (40×M-M, 40×M-F, 40×F-F)"},{"key":"Length","value":"20 cm"},{"key":"Wire","value":"26 AWG flexible"},{"key":"Colours","value":"10 colours"},{"key":"Pitch","value":"2.54 mm"},{"key":"Max Voltage","value":"300V"}]',
  9, 6, 0, 0, 1, 0, NULL, NULL, 1
),
(
  'USB Type-C Cable Pack — 1m, 3 Cables', 'usb-type-c-cable-pack-3pcs',
  'Pack of 3 USB-A to USB-C braided nylon cables, 1 m each. 480 Mbps data + 3A fast charging. Compatible with ESP32-S3, Arduino Nano 33, Raspberry Pi 5 and any USB-C device.',
  299.00, 449.00, 33.40, 500,
  'https://picsum.photos/seed/usb-c-cable-pack/600/600',
  '["https://picsum.photos/seed/usb-c-cable-pack/600/600"]',
  '["usb-c","cable","charging","data","braided","3-pack"]',
  '[{"key":"Length","value":"1 m"},{"key":"Qty","value":"3 cables"},{"key":"Connector A","value":"USB-A 2.0"},{"key":"Connector B","value":"USB-C"},{"key":"Speed","value":"480 Mbps"},{"key":"Charging","value":"3A max"},{"key":"Jacket","value":"Braided nylon"}]',
  9, 6, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── KITS (cat 10) ────────────────────────────────────────────
(
  'Arduino Ultimate Starter Kit — 32 Components', 'arduino-ultimate-starter-kit',
  'Everything to start with Arduino — Uno R3 clone, 830-point breadboard, 32 component types (LEDs, resistors, sensors, servo, LCD, buzzer, relay, motors) and 30+ project PDFs.',
  1499.00, 2299.00, 34.80, 60,
  'https://picsum.photos/seed/arduino-starter-kit/600/600',
  '["https://picsum.photos/seed/arduino-starter-kit/600/600","https://picsum.photos/seed/arduino-starter-kit-b/600/600"]',
  '["kit","arduino","starter","beginner","learning","diy"]',
  '[{"key":"Board","value":"Arduino Uno R3 compatible"},{"key":"Breadboard","value":"830-point"},{"key":"Components","value":"32 types"},{"key":"Jumper Wires","value":"65 pcs"},{"key":"USB Cable","value":"Included"},{"key":"Projects","value":"30+ PDF tutorials"}]',
  10, 1, 1, 0, 1, 0, NULL, NULL, 1
);


-- ============================================================
-- 6. DIY KITS  (3 rows)
-- ============================================================
INSERT IGNORE INTO `diy_kits`
  (`id`, `name`, `description`, `price`, `thumbnail`, `images`, `components`, `difficulty`, `stock`)
VALUES
(
  1,
  'Arduino IoT Weather Station Kit',
  'Build a cloud-connected IoT weather station that logs temperature, humidity, and air pressure. Displays live data on an OLED screen and sends WhatsApp alerts. Step-by-step video tutorial and custom PCB included.',
  1299.00,
  'https://picsum.photos/seed/iot-weather-kit/600/600',
  '["https://picsum.photos/seed/iot-weather-kit/600/600","https://picsum.photos/seed/iot-weather-kit-b/600/600"]',
  '[{"name":"ESP32 DevKit V1","qty":1},{"name":"DHT22 Sensor","qty":1},{"name":"BMP280 Pressure Sensor","qty":1},{"name":"0.96 inch OLED Display","qty":1},{"name":"830-pt Breadboard","qty":1},{"name":"Jumper Wires 40pcs","qty":1},{"name":"USB-C Cable","qty":1},{"name":"Custom PCB","qty":1},{"name":"3D Printed Enclosure","qty":1},{"name":"Project PDF Guide","qty":1}]',
  'beginner', 40
),
(
  2,
  'Raspberry Pi Security Camera Kit',
  'Build a smart security camera with Pi Pico W + OV2640. Streams live video over WiFi. Motion detection sends a Telegram alert with snapshot. Includes 3D-printed dome and complete Python source code.',
  2499.00,
  'https://picsum.photos/seed/security-camera-kit/600/600',
  '["https://picsum.photos/seed/security-camera-kit/600/600","https://picsum.photos/seed/security-camera-kit-b/600/600"]',
  '[{"name":"Raspberry Pi Pico W","qty":1},{"name":"OV2640 Camera Module","qty":1},{"name":"PIR Motion Sensor","qty":1},{"name":"IR LED Board 850nm","qty":1},{"name":"5V 3A USB-C PSU","qty":1},{"name":"MicroSD Card 16GB","qty":1},{"name":"3D Printed Dome Enclosure","qty":1},{"name":"M2 Mounting Hardware","qty":1},{"name":"Python Source Code USB","qty":1}]',
  'intermediate', 25
),
(
  3,
  'Line-Following Robot Car Kit',
  'Build a robot car that autonomously follows a black line using 5-sensor IR array + PID control. Includes assembled chassis, motor driver, OLED, and pre-written Arduino code. Expand with Bluetooth control.',
  1799.00,
  'https://picsum.photos/seed/line-follower-robot-kit/600/600',
  '["https://picsum.photos/seed/line-follower-robot-kit/600/600","https://picsum.photos/seed/line-follower-robot-kit-b/600/600"]',
  '[{"name":"Arduino Nano Every","qty":1},{"name":"L298N Motor Driver","qty":1},{"name":"2WD Robot Chassis","qty":1},{"name":"5-Channel IR Sensor Array","qty":1},{"name":"HC-05 Bluetooth Module","qty":1},{"name":"0.96 inch OLED Display","qty":1},{"name":"18650 Battery Holder 2-cell","qty":1},{"name":"18650 Li-Ion Batteries","qty":2},{"name":"Track Sheet A0","qty":1},{"name":"Code + App APK","qty":1}]',
  'intermediate', 30
);


-- ============================================================
-- 7. BLOG POSTS  (3 rows)
-- ============================================================
INSERT IGNORE INTO `blogs`
  (`id`, `title`, `slug`, `excerpt`, `content`, `thumbnail`,
   `author_name`, `category`, `tags`, `reading_time`, `view_count`)
VALUES
(
  1,
  'Getting Started with ESP32: Your First IoT Project',
  'getting-started-esp32-first-iot-project',
  'The ESP32 is the best value development board in 2026. This guide walks through setting up Arduino IDE, connecting to WiFi, and building a temperature dashboard you can check from your phone.',
  '<h2>Why ESP32?</h2><p>The ESP32 packs a dual-core 240 MHz processor, 520 KB SRAM, WiFi, Bluetooth, and 38 GPIO pins — all for under ₹450. The most cost-effective path to connected hardware in India.</p><h2>What You Need</h2><ul><li>ESP32 DevKit V1</li><li>DHT22 sensor</li><li>Breadboard + jumper wires</li><li>USB-C cable</li><li>Arduino IDE 2.x with ESP32 board package</li></ul><h2>Step 1 — Install Board Package</h2><p>In Arduino IDE go to <strong>File → Preferences</strong> and add the Espressif ESP32 board manager URL. Open Board Manager, search <em>esp32</em>, install version 3.x.</p><h2>Step 2 — Wire DHT22</h2><p>DHT22 VCC → 3.3V, GND → GND, DATA → GPIO4 with a 10kΩ pull-up to 3.3V.</p><h2>Step 3 — Read & Push Data</h2><p>Install the DHT sensor library by Adafruit. The sketch reads temperature every 10 seconds and posts it to a free ThingSpeak channel via WiFiClient.</p><h2>Next Steps</h2><p>Add an OLED display for local readings, or use ESP32 BLE to stream data to your phone.</p>',
  'https://picsum.photos/seed/esp32-blog-post/1200/630',
  'Tejas Sharma', 'Tutorial',
  '["esp32","iot","wifi","dht22","arduino","tutorial","beginner"]',
  7, 312
),
(
  2,
  'Top 5 Sensors Every Maker Should Own in 2026',
  'top-5-sensors-every-maker-should-own-2026',
  'The right sensor makes all the difference. We picked the 5 most versatile, affordable sensors available in India — with code examples and project ideas for each.',
  '<h2>1. DHT22 — Temperature & Humidity</h2><p>More accurate than DHT11 (±0.5°C vs ±2°C), calibrated from factory, single GPIO pin. At ₹199 it is the best climate sensor per rupee. Use in weather stations, plant monitors, and HVAC control.</p><h2>2. HC-SR04 — Ultrasonic Distance</h2><p>2 cm to 4 m range, 3 mm accuracy. Trigger a 10 µs pulse, divide echo time by 58 for centimetres. Used in parking sensors, bin-level monitors, and robot obstacle detection. Under ₹100.</p><h2>3. MPU-6050 — 6-Axis IMU</h2><p>Accelerometer + gyroscope in one chip with built-in DMP for pitch/roll/yaw. Essential for drones, balancing robots, and motion-controlled displays.</p><h2>4. MQ-135 — Air Quality</h2><p>Detects CO2, NH3, benzene, and smoke via an analog output. Combine with an ESP32 and 0.96" OLED for a pocket air quality badge.</p><h2>5. Soil Moisture Sensor</h2><p>Resistive probes measure conductivity of soil. Pair with a relay and solenoid valve for an automatic irrigation system. Perfect for balconies and greenhouses.</p><h2>Where to Buy</h2><p>All five are available individually or in our <strong>Arduino Ultimate Starter Kit</strong> with free shipping above ₹499.</p>',
  'https://picsum.photos/seed/sensors-blog-post/1200/630',
  'Priya Nair', 'Buying Guide',
  '["sensors","dht22","hcsr04","mpu6050","mq135","maker","buying-guide"]',
  5, 487
),
(
  3,
  'How to Design and Print Your First 3D Part: A Beginner Guide',
  'design-print-first-3d-part-beginner-guide',
  'Going from idea to physical object in a few hours is one of the most satisfying experiences in making. This guide covers the full workflow — FreeCAD to Cura to our 3D printing service.',
  '<h2>Step 1 — Design Your Part</h2><p>Use <strong>FreeCAD</strong> (free) or <strong>Fusion 360</strong> (free for hobbyists). Model your part and export as .STL. Key FDM rules: minimum wall 1.2 mm, avoid overhangs beyond 45° without supports, add 0.2 mm clearance between mating parts.</p><h2>Step 2 — Choose Material</h2><p><strong>PLA</strong> — easiest to print, ideal for enclosures indoors below 50°C. <strong>PETG</strong> — moisture and chemical resistant, good for outdoor use. <strong>ABS</strong> — high-temperature tolerance but warps easily, needs an enclosure.</p><h2>Step 3 — Slice in Cura</h2><p>Open your STL in Ultimaker Cura. Recommended PLA settings: layer height 0.2 mm, infill 15% (display) or 40% (structural), print speed 50 mm/s, supports touching build plate only.</p><h2>Step 4 — Use Our Service</h2><p>No printer? Upload your STL on TT Electro''s 3D Printing page, choose material, quantity, and colour. Quote within 4 hours. Lead time 2–3 business days.</p>',
  'https://picsum.photos/seed/3dprinting-blog-post/1200/630',
  'Rohan Mehta', 'Tutorial',
  '["3d-printing","freecad","cura","pla","petg","beginner","guide"]',
  8, 218
);


-- ============================================================
-- 8. OFFERS  (3 rows)
-- ============================================================
INSERT IGNORE INTO `offers`
  (`id`, `title`, `description`, `type`, `discount`, `ends_at`, `badge`)
VALUES
(
  1,
  'Flash Sale — Sensors Up to 40% Off',
  'Limited-time flash sale on our most popular sensor modules. DHT22, MPU-6050, and NRF24L01 at slashed prices. No coupon needed — prices already reduced.',
  'flash', '40%', '2026-08-31 23:59:59', 'Flash Sale'
),
(
  2,
  'First Order 10% Off with TTFIRST',
  'New to TT Electro? Use code TTFIRST at checkout for 10% off your first order (up to ₹500). Valid on orders above ₹299. One use per customer.',
  'coupon', '10%', NULL, 'New Customer'
),
(
  3,
  'Maker Bundle Deal — Spend ₹999+ Save 20%',
  'Use code MAKER20 on orders above ₹999 to save 20% (up to ₹800). Perfect when stocking up on sensors, modules, and that starter kit you have been eyeing.',
  'coupon', '20%', NULL, 'Maker Deal'
);


-- ============================================================
-- 9. COUPONS  (3 rows)
-- ============================================================
INSERT IGNORE INTO `coupons`
  (`code`, `discount_type`, `discount`, `min_order_amount`, `max_discount`, `is_active`)
VALUES
  ('TTFIRST', 'percent', 10.00, 299.00,  500.00, 1),
  ('MAKER20', 'percent', 20.00, 999.00,  800.00, 1),
  ('FLAT150', 'fixed',  150.00, 599.00,    NULL, 1);


-- ============================================================
-- 10. FAQ  (10 rows — 5 categories)
-- ============================================================
INSERT IGNORE INTO `faq` (`question`, `answer`, `category`)
VALUES
  ('Do you ship across India?',
   'Yes! We deliver to all major cities and towns across India via trusted courier partners like DTDC, BlueDart, and Ekart.',
   'Shipping'),
  ('How long does delivery take?',
   'Standard delivery takes 5–7 business days. Express delivery (2–3 days) is available for select pin codes. You will receive a tracking link once your order is dispatched.',
   'Shipping'),
  ('Is free shipping available?',
   'Yes! Orders above ₹499 qualify for free standard shipping anywhere in India.',
   'Shipping'),
  ('Is Cash on Delivery available?',
   'Yes, COD is available for orders up to ₹10,000. UPI, Net Banking, and Card payments are also accepted at checkout.',
   'Payment'),
  ('Which UPI apps do you accept?',
   'We accept all major UPI apps — Google Pay, PhonePe, Paytm, BHIM, and any bank UPI ID.',
   'Payment'),
  ('How do I track my order?',
   'Go to the "Track Order" page and enter your order number (starts with TTE-). You can also track from your account dashboard when logged in.',
   'Orders'),
  ('Can I cancel or modify my order?',
   'Orders can be cancelled within 2 hours of placing them. After dispatch, cancellation is not possible, but you may initiate a return after delivery.',
   'Orders'),
  ('What is the return policy?',
   'We accept returns within 7 days of delivery for defective or damaged products. Contact support with your order number and photos of the issue.',
   'Returns'),
  ('How long do refunds take?',
   'Refunds are processed within 5–7 business days after we receive and verify the returned item.',
   'Returns'),
  ('Do you sell genuine products?',
   'Absolutely! We source directly from authorised distributors and official brand channels. Every product is quality-checked before dispatch.',
   'Products');


-- ============================================================
-- 11. REVIEWS  (10 rows)
-- product_id values match the 25 products inserted above (ids 1–25)
-- user_id = 2 (admin) used as demo author
-- ============================================================
INSERT IGNORE INTO `reviews`
  (`id`, `product_id`, `user_id`, `rating`, `title`, `body`)
VALUES
  (1,  1,  2, 5, 'Exactly as described',
   'My Arduino Uno R3 arrived well packed and worked out of the box. Tested with the blink example — all 14 digital pins checked out. Great quality for the price.'),
  (2,  1,  2, 4, 'Good board, minor shipping delay',
   'Solid Uno clone. Took 6 days to reach Hyderabad but the board itself is perfect. Loaded several sketches without issues.'),
  (3,  3,  2, 5, 'Best ESP32 deal on the market',
   'The DevKit V1 connected to my router in 2 seconds. Running a web server and MQTT client simultaneously with no instability after 3 weeks.'),
  (4,  3,  2, 5, 'WiFi + BLE at this price is unreal',
   'Switching from NodeMCU — the dual-core and larger RAM make a huge difference for FreeRTOS projects. Highly recommend.'),
  (5,  4,  2, 5, 'Pico W is a game-changer',
   'MicroPython runs beautifully. Set up an HTTP sensor dashboard in under an hour. 26 GPIO + WiFi at this price is incredible.'),
  (6,  6,  2, 5, 'Most reliable ultrasonic sensor',
   'Tested 10 units for a batch robotics project — all within 3 mm accuracy across the 2–300 cm range. Excellent consistency.'),
  (7,  9,  2, 5, 'OLED looks amazing on my project',
   'Crystal clear even in bright sunlight. I2C address was 0x3C as advertised. Works perfectly with the Adafruit SSD1306 library.'),
  (8,  14, 2, 5, 'HC-05 paired with my phone in 10 seconds',
   'Changed baud rate to 115200 via AT commands without any trouble. Running Bluetooth serial control for my rover now.'),
  (9,  20, 2, 4, 'Solid motor driver, runs a little warm',
   'Drives two 12V motors smoothly. Add a heat sink near 2A — it gets warm but that is normal for L298N. Great value.'),
  (10, 25, 2, 5, 'Perfect first kit, zero missing parts',
   'Everything was bagged and labelled. Completed 8 projects in the first week. My 14-year-old nephew is hooked on electronics now!');


-- ============================================================
SET FOREIGN_KEY_CHECKS = 1;
-- ============================================================
-- DONE!  All rows use INSERT IGNORE — safe to run repeatedly.
--
-- Summary:
--   users      4  (guest · admin · 2 demo customers)
--   categories 10
--   brands      6
--   banners     3
--   products   25  (all 10 categories)
--   diy_kits    3
--   blogs       3
--   offers      3
--   coupons     3
--   faq        10
--   reviews    10
--
-- Admin login:  admin@ttelectro.in  /  Admin@123
-- ============================================================
