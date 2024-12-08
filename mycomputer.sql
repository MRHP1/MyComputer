-- Membuat tabel RAM
CREATE TABLE RAM (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    generation VARCHAR(10),
    speed VARCHAR(10),
    price DECIMAL(10,2),
    link TEXT
);

-- Mengisi data ke dalam tabel RAM
INSERT INTO RAM (name, generation, speed, price, link) VALUES
('G.Skill TRIDENT Z RGB 32GB (2x16GB)', 'DDR4', '3600Mhz', 1545000, 'https://www.tokopedia.com/tokoexpert/g-skill-trident-z-rgb-ddr4-32gb-2x16gb-3600mhz-dual-channel'),
('Kingston HyperX FURY 16GB', 'DDR4', '3200Mhz', 539000, 'https://www.tokopedia.com/fireinc/kingston-hyperx-fury-desktop-ddr4-ram-8gb-16gb-dimm-game-memory-16gb-3200mhz-1-bbc9b'),
('Corsair Vengeance LPX (2x8) 16GB', 'DDR4', '3200MHz', 679000, 'https://www.tokopedia.com/gasol/corsair-vengeance-lpx-2x8-16gb-ddr4-kit-3200mhz-cmk16gx4m2e3200c16'),
('TEAMGROUP T-Create 64GB (2 x 32 GB)', 'DDR5', '6000Mhz', 3010000, 'https://www.tokopedia.com/gamingpcstore/teamgroup-t-create-expert-overclocking-ddr5-64gb-2x32gb-6000mhz-cl38-memory-ram-pc'),
('ADATA DDR5 XPG 32GB (2X16GB)', 'DDR5', '5600Mhz', 1386000, 'https://www.tokopedia.com/enterkomputer/adata-ddr5-xpg-lancer-blade-white-pc44800-32gb-2x16gb-ram-ddr5-32gb'),
('VenomRX Lodimm 16GB', 'DDR4', '3200Mhz', 448500, 'https://www.tokopedia.com/multikomputer201/venomrx-desktop-ram-lodimm-ddr4-16gb-pc3200');

-- Membuat tabel Storage
CREATE TABLE Storage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    read_speed VARCHAR(50),
    write_speed VARCHAR(50),
    form_factor VARCHAR(10),
    price DECIMAL(10,2),
    link TEXT
);

-- Mengisi data ke dalam tabel Storage
INSERT INTO Storage (name, read_speed, write_speed, form_factor, price, link) VALUES
('SSD EYOTA 256GB', 'Up To 500 MB/s', 'Up To 500 MB/s', 'Sata3', 197000, 'https://tokopedia.link/2WBTvOlN9Ob'),
('Samsung SSD 980 500GB', '2900 MB/s', '1300 MB/s', 'M.2', 955000, 'https://tokopedia.link/cQxR0GvN9Ob'),
('Samsung SSD 870 EVO 250GB', 'Up to 560 MB/s', 'Up to 530 MB/s', 'Sata3', 669000, 'https://tokopedia.link/TVyGjhCN9Ob'),
('KAIZEN M2NVME 512GB', 'up to 2100Mb/s', 'up to 1700Mbps', 'M.2', 420000, 'https://tokopedia.link/y1QA4FJN9Ob'),
('RX7 SSD 128GB', 'up to 550 mb/s', 'up to 530 mb/s', '2.5', 121000, 'https://tokopedia.link/7qBw8YON9Ob'),
('HDD WD 500GB', 'up to 120 mb/s', 'up to 100 mb/s', '3.5', 100000, 'https://tokopedia.link/HbchpAZN9Ob'),
('SSD EYOTA 1TB', 'Up To 500 MB/s', 'Up To 500 MB/s', 'Sata3', 750000, 'https://tokopedia.link/Ziq91rsX9Ob');

-- Membuat tabel GPU
CREATE TABLE GPU (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    vram VARCHAR(10),
    recommended_psu VARCHAR(50),
    price DECIMAL(10,2),
    link TEXT
);

-- Mengisi data ke dalam tabel GPU
INSERT INTO GPU (name, vram, recommended_psu, price, link) VALUES
('MSI GeForce RTX 3060 VENTUS 2X OC', '12GB', '550 Watt', 4990000, 'https://www.tokopedia.com/gasol/msi-geforce-rtx-3060-ventus-2x-oc-12gb-gddr6-vga-ampere-rtx3060-ddr6'),
('Colorful GeForce GT710', '2GB', '400 Watt', 575000, 'https://www.tokopedia.com/dbclick/vga-colorful-geforce-gt710-2gd3-v'),
('Midasforce AMD RX 580', '8GB', '450 Watt', 1480000, 'https://www.tokopedia.com/it-shoponline/vga-midasforce-amd-rx-580-8gb-gddr5-256bit-vga-midas-rx-580'),
('MSI GTX 1660 Super Ventus XS OC', '6GB', '450 Watt', 3650000, 'https://www.tokopedia.com/dbclick/vga-card-msi-gtx-1660-super-ventus-xs-oc-vga-msi-gtx1660-super-ventus'),
('Asus Dual GeForce Rtx 4060 Ti', '8GB', '650 Watt', 7329000, 'https://www.tokopedia.com/dbclick/vga-asus-dual-geforce-rtx-4060-ti-o8g-gddr6-hdmi-dp-rtx-4060-ti-8gb'),
('Asus Dual RTX 3050 O8G V2', '8GB', '550 Watt', 3950000, 'https://www.tokopedia.com/dbclick/vga-card-asus-dual-rtx-3050-o8g-v2-8gb-gddr6-hdmi-dp'),
('ZOTAC GEFORCE GTX 750 Ti', '2GB', '450 Watt', 735200, 'https://www.tokopedia.com/midescomp/vga-card-zotac-geforce-gtx-750-ti-2gb-2fan-750-ti-fe6f6'),
('ASRock Radeon RX 6600', '8GB', '500 Watt', 3315000, 'https://www.tokopedia.com/tokoexpert/asrock-radeon-rx-6600-challenger-d-8gb-gddr6'),
('ASROCK AMD Radeon RX 6700XT', '12GB', '650 Watt', 3399000, 'https://www.tokopedia.com/redteknologi/asrock-amd-radeon-rx-6700xt-12gb-challenger-pro-oc-2nd'),
('Sapphire Radeon RX 7600', '8GB', '400 Watt', 4423000, 'https://www.tokopedia.com/enterkomputer/sapphire-radeon-rx-7600-8gb-gddr6-pulse'),
('SPEEDSTER SWFT AMD RADEON RX 7800 XT', '16GB', '700 Watt', 7870000, 'https://tokopedia.link/2nm9DeA99Ob');

-- Membuat tabel PC_Case
CREATE TABLE PC_Case (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    type VARCHAR(50),
    dimensions VARCHAR(50),
    price DECIMAL(10,2),
    link TEXT
);

-- Mengisi data ke dalam tabel PC_Case
INSERT INTO PC_Case (name, type, dimensions, price, link) VALUES
('NZXT H9 Flow', 'ATX Mid Tower', '466 mm x 290 mm x 495 mm', 2375000, 'https://www.tokopedia.com/cockomputer/nzxt-h9-flow-black'),
('LIAN LI PC-O11 DYNAMIC', 'ATX Full Tower', '445 mm x 272 mm x 446 mm', 3338000, 'https://www.tokopedia.com/cockomputer/lian-li-pc-o11-dynamic-xl-rog-white'),
('Fractal Design Meshify', 'ATX Mid Tower', '413 mm x 217 mm x 453 mm', 1100000, 'https://www.tokopedia.com/fractal-design/fractal-design-meshify-2-mini-m-atx-gaming-case-white-clear-tin'),
('Phanteks Eclipse P600S', 'ATX Mid Tower', '240mm x 520mm x 510mm', 2550000, 'https://www.tokopedia.com/julyaugustshop/phanteks-eclipse-p600s-satin-black'),
('AULA ARCTIC MG10', 'ATX Mid Tower', '315mm x 185mm x 420mm', 385000, 'https://www.tokopedia.com/dbclick/casing-pc-gaming-aula-arctic-mg10-m-atx-include-3-fan-gaming-case-black-78337'),
('PARADOX GAMING COSMIC', 'ATX Mid Tower', '443mm x 275mm x 435mm', 755000, 'https://www.tokopedia.com/gamingpcstore/paradox-gaming-cosmic-m-free-3-fan-argb-micro-atx-case-casing-pc-white-edition'),
('Segotep ENDURA PRO+', 'ATX Mid Tower', '436mm x 230mm x 456mm', 779000, 'https://www.tokopedia.com/dynamix/segotep-endura-pro-airflow-tempered-glass-e-atx-pc-case-black'),
('CORSAIR 3000D AIRFLOW', 'ATX Mid Tower', '466mm x 462mm x 230mm', 1190000, 'https://www.tokopedia.com/lezzcom/corsair-3000d-airflow-mid-tower-pc-case-white'),
('COUGAR PANZER MAX', 'ATX Full Tower', '266mm x 612mm x 556mm', 2350000, 'https://www.tokopedia.com/sportonlineshop/cougar-panzer-max-full-tower-pc-case-casing-gaming-chassis'),
('NYK Nemesis T10 Scylla', 'ATX Mid Tower', '350mm x 180mm x 420mm', 270000, 'https://www.tokopedia.com/httptokom4nt4p/nyk-nemesis-casing-komputer-gaming-t10-scylla-pc-case-hitam'),
('ARMOUR AR-01', 'ATX Mid Tower', '310mm x 200mm x 450mm', 195000, 'https://www.tokopedia.com/kadalgamingtech/casing-pc-office-standart-tanpa-psu-armour-ar-01-case-new-m-atx'),
('Case Komputer Bekas', 'ATX Mid Tower', '470mm x 280mm x 435mm', 70000, 'https://www.tokopedia.com/nusantaracomp/casing-case-cpu-pc-komputer-computer');

-- Membuat tabel PSU
CREATE TABLE PSU (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    wattage VARCHAR(50),
    certification VARCHAR(50),
    price DECIMAL(10,2),
    link TEXT
);

-- Mengisi data ke dalam tabel PSU
INSERT INTO PSU (name, wattage, certification, price, link) VALUES
('MSI MAG A550BN', '550 Watt', '80+ Bronze', 701000, 'https://www.tokopedia.com/dbclick/power-supply-msi-mag-a550bn-550watt-80-bronze-psu-msi-mag-a550-bn'),
('Corsair CX650', '650 Watt', '80+ Bronze', 865000, 'https://www.tokopedia.com/dbclick/power-supply-corsair-cx650-650-watt-80-plus-bronze-certified-psu-cx-650-650watt'),
('Corsair RM650', '650 Watt', '80+ Gold', 1499000, 'https://www.tokopedia.com/dynamix/corsair-psu-rm650-80plus-gold-certified-fully-modular'),
('DEEPCOOL PN750D', '750 Watt', '80+ Gold', 1130000, 'https://www.tokopedia.com/ciptamandiricomp/psu-deepcool-pn750d-80plus-gold-750w'),
('Fractal Design Ion+ 2', '760 Watt', '80+ Platinum', 1648000, 'https://www.tokopedia.com/enterkomputer/fractal-design-ion-2-760w-80-platinum-fully-modular-psu-760w'),
('Corsair HX1000i', '1000 Watt', '80+ Platinum', 3995000, 'https://www.tokopedia.com/eseskomputer/psu-corsair-hxi-series-hx1000i-1000watt-80-plus-platinum-fully-modular');

-- Membuat tabel CPU
CREATE TABLE CPU (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    core INT,
    core_clock VARCHAR(50),
    boost_clock VARCHAR(50),
    microarchitecture VARCHAR(50),
    tdp VARCHAR(50),
    igpu VARCHAR(50),
    rating INT,
    price DECIMAL(10,2),
    link TEXT
);

-- Mengisi data ke dalam tabel CPU
INSERT INTO CPU (name, core, core_clock, boost_clock, microarchitecture, tdp, igpu, rating, price, link) VALUES
('AMD Ryzen 5 5500', 6, '3.6 GHz', '4.2 GHz', 'Zen 3', '65 W', 'None', -55, 1194000, 'https://tokopedia.link/QDLaWB0U9Ob'),
('Intel Core i5-12400F', 6, '2.5 GHz', '4.4 GHz', 'Alder Lake', '65 W', 'None', -86, 1886000, 'https://tokopedia.link/QDLaWB0U9Ob'),
('AMD Ryzen 7 5700X', 8, '3.4 GHz', '4.6 GHz', 'Zen 3', '65 W', 'None', -101, 2537060, 'https://tokopedia.link/OVNe0r4U9Ob'),
('AMD Ryzen 5 5600', 6, '3.5 GHz', '4.4 GHz', 'Zen 3', '65 W', 'None', -156, 1488960, 'https://tokopedia.link/liyhLi6U9Ob'),
('Intel Core i7-12700K', 12, '3.6 GHz', '5 GHz', 'Alder Lake', '125 W', 'Intel UHD Graphics 770', -145, 4050000, 'https://tokopedia.link/TswuFcaV9Ob'),
('AMD Ryzen 5 3600', 6, '3.6 GHz', '4.2 GHz', 'Zen 2', '65 W', 'None', -1169, 999000, 'https://tokopedia.link/aQJb7WnV9Ob'),
('Intel Core i9-12900K', 16, '3.2 GHz', '5.2 GHz', 'Alder Lake', '125 W', 'Intel UHD Graphics 770', -63, 5750000, 'https://tokopedia.link/kL8lcnnV9Ob'),
('Intel Core i5-12600K', 10, '3.7 GHz', '4.9 GHz', 'Alder Lake', '125 W', 'Intel UHD Graphics 770', -151, 3120000, 'https://tokopedia.link/tcZrmNpV9Ob'),
('AMD Ryzen 5 5600G', 6, '3.9 GHz', '4.4 GHz', 'Zen 3', '65 W', 'Radeon Vega 7', -144, 1830000, 'https://tokopedia.link/pHGqK4MV9Ob'),
('Intel Core i3-12100F', 4, '3.3 GHz', '4.3 GHz', 'Alder Lake', '58 W', 'None', -49, 1318000, 'https://tokopedia.link/oomn7POV9Ob'),
('AMD Ryzen 9 5950X', 16, '3.4 GHz', '4.9 GHz', 'Zen 3', '105 W', 'None', -107, 7026001, 'https://tokopedia.link/vvvMbqQV9Ob'),
('AMD Ryzen 7 5700G', 8, '3.8 GHz', '4.6 GHz', 'Zen 3', '65 W', 'Radeon Vega 8', -47, 2959000, 'https://tokopedia.link/pl4CmeUV9Ob'),
('Intel Core i5-10400F', 6, '2.9 GHz', '4.3 GHz', 'Comet Lake', '65 W', 'None', -59, 1659000, 'https://tokopedia.link/cvC4RbWV9Ob'),
('Intel Core i7-10700K', 8, '3.8 GHz', '5.1 GHz', 'Comet Lake', '125 W', 'Intel UHD Graphics 630', -101, 4395001, 'https://tokopedia.link/6GjFfP2V9Ob'),
('Intel Core i3-12100', 4, '3.3 GHz', '4.3 GHz', 'Alder Lake', '60 W', 'Intel UHD Graphics 730', -15, 1739000, 'https://tokopedia.link/z4vRup4V9Ob'),
('Intel Core i3-10100F', 4, '3.6 GHz', '4.3 GHz', 'Comet Lake', '65 W', 'None', -26, 1150000, 'https://tokopedia.link/OWvCfZ6V9Ob'),
('Intel Core i9-10900K', 10, '3.7 GHz', '5.3 GHz', 'Comet Lake', '125 W', 'Intel UHD Graphics 630', -51, 5535001, 'https://tokopedia.link/ApTvws8V9Ob'),
('Intel Core i5-10400', 6, '2.9 GHz', '4.3 GHz', 'Comet Lake', '65 W', 'Intel UHD Graphics 630', -63, 1869001, 'https://tokopedia.link/4fM39CaW9Ob'),
('Intel Core i5-10600K', 6, '4.1 GHz', '4.8 GHz', 'Comet Lake', '125 W', 'Intel UHD Graphics 630', -72, 2990001, 'https://tokopedia.link/xmgce0dW9Ob'),
('Intel Core i7-3770', 4, '3.4 GHz', '3.4 GHz', 'Ivy Bridge', '77 W', 'Intel HD Graphics 4000', -49, 459001, 'https://tokopedia.link/Oa4uP3nW9Ob'),
('Intel Core i5-3470', 4, '3.2 GHz', '3.2 GHz', 'Ivy Bridge', '77 W', 'Intel HD Graphics 2500', -36, 189500, 'https://tokopedia.link/jSvnd2qW9Ob'),
('Intel Core i9-10900F', 10, '2.8 GHz', '5.2 GHz', 'Comet Lake', '65 W', 'None', -4, 3499001, 'https://tokopedia.link/tIEyVgtW9Ob'),
('Intel Core i9-14900K', 24, '3.2 GHz', '6 GHz', 'Raptor Lake Refresh', '125 W', 'Intel UHD Graphics 770', -32, 7380000, 'https://tokopedia.link/z6mzJ9O29Ob'),
('AMD Ryzen 7 5700X3D', 8, '3 GHz', '4.1 GHz', 'Zen 3', '105 W', 'None', -16, 3819000, 'https://tokopedia.link/7LfyvGc99Ob'),
('AMD Ryzen 7 7800X3D', 8, '4.2 GHz', '5 GHz', 'Zen 4', '120 W', 'Radeon', -432, 7390001, 'https://tokopedia.link/qi0LVNj99Ob'),
('AMD Ryzen 7 7700X', 8, '4.5 GHz', '5.4 GHz', 'Zen 4', '105 W', 'Radeon', -143, 5323000, 'https://tokopedia.link/p6nJ8CW99Ob');

-- Membuat tabel CPU_Cooler
CREATE TABLE CPU_Cooler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    rpm VARCHAR(50),
    noise VARCHAR(50),
    price DECIMAL(10,2),
    link TEXT
);

-- Mengisi data ke dalam tabel CPU_Cooler
INSERT INTO CPU_Cooler (name, rpm, noise, price, link) VALUES
('THERMALEASE GT390', '1500-2300', '22.7dB', 199000, 'https://www.tokopedia.com/kyo-official/thermalease-gt390-cpu-air-cooler-fan-single-tower-3-heatpipe-kyo-group-hsf-fan-air-cooling-black-1c7f9'),
('KYO SAMA KA400IW', '800-1800', '36dB', 349000, 'https://www.tokopedia.com/rajaramnusantara/kyo-sama-ka400iw-cpu-cooler-argb-fan-processor-cooler-argb-4-heatpipe'),
('AMD Wraith Stealth', '1600', '25dB', 97800, 'https://www.tokopedia.com/future168/amd-wraith-stealth-coller-hsf-bawaan-ryzen'),
('JONSBO FAN CR-1200', '2300', '30.5Db', 150000, 'https://www.tokopedia.com/dnetwork-1/jonsbo-fan-cr-1200-prosessor-cpu-cooler-hsf-cooler-rgb-coolmon-double'),
('Thermaltake ASTRIA 400', '500 ~ 1800', '26.8 dB', 670000, 'https://www.tokopedia.com/thermaltake/thermaltake-astria-400-argb-lighting-6-heatpipe-cpu-air-cooler'),
('NZXT Kraken Elite RGB', '500 - 2400', '30 dB', 4820000, 'https://www.tokopedia.com/tokoexpert/nzxt-kraken-elite-360-rgb-aio-liquid-cooling-white'),
('Cooler Master Masterliquid 240', '690 - 2500', '27.2 dB', 1999000, 'https://www.tokopedia.com/coolermaster-id/cooler-master-masterliquid-240-atmos'),
('ASUS ROG RYUJIN III 360', '450 - 2000', '29.7 dB', 5650000, 'https://www.tokopedia.com/lezzcom/asus-rog-ryujin-iii-360-argb-360mm-aio-liquid-cpu-cooler-with-lcd'),
('Digital Alliance KAZE 360', '2600', '25dB', 679000, 'https://www.tokopedia.com/it-shoponline/digital-alliance-kaze-360-argb-liquid-cpu-cooler-black'),
('THERMALRIGHT AXP90-X53', '2700', '22.4 dB', 939000, 'https://www.tokopedia.com/berliancom/thermalright-axp90-x53-full-copper-low-profile-cpu-cooler-intel-amd');

-- Membuat tabel Motherboard
CREATE TABLE Motherboard (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    socket VARCHAR(50),
    form_factor VARCHAR(50),
    memory_max VARCHAR(50),
    memory_slots INT,
    price DECIMAL(10,2),
    link TEXT
);

-- Mengisi data ke dalam tabel Motherboard
INSERT INTO Motherboard (name, socket, form_factor, memory_max, memory_slots, price, link) VALUES
('ASRock A520M', 'AM4', 'm-ATX', '64 GB', 2, 839000, 'https://tokopedia.link/COPgfP3K9Ob'),
('Asus PRIME B450M', 'AM4', 'm-ATX', '64GB', 2, 949000, 'https://tokopedia.link/AiHDZD2K9Ob'),
('MSI B450M Pro', 'AM4', 'm-ATX', '64GB', 4, 990000, 'https://tokopedia.link/ruzRDr1K9Ob'),
('Asus PRIME B450M-A II', 'AM4', 'm-ATX', '128GB', 4, 1130000, 'https://tokopedia.link/UAuJYSXK9Ob'),
('Varro Intel H110', 'LGA1151', 'm-ATX', '32GB', 2, 632000, 'https://tokopedia.link/sLBdz8TK9Ob'),
('KYO KAIZEN H110', 'LGA1151', 'm-ATX', '32GB', 2, 599000, 'https://tokopedia.link/MazoPO8K9Ob'),
('Motherboard Asrock A620M-HDV', 'AM5', 'm-ATX', '64GB', 2, 1559000, 'https://tokopedia.link/KHvrWpkL9Ob'),
('MSI PRO B650M-A WIFI', 'AM5', 'm-ATX', '128GB', 4, 2489000, 'https://tokopedia.link/taGdjQjL9Ob'),
('ASROCK B560M-HDV/M.2', 'LGA1200', 'm-ATX', '64GB', 2, 1158000, 'https://tokopedia.link/ikSVT4hX9Ob'),
('KYO KAIZEN H610', 'LGA 1700', 'm-ATX', '64GB', 2, 835000, 'https://tokopedia.link/8Mc6GxMX9Ob'),
('MSI PRO B660M-G DDR4', 'LGA 1700', 'm-ATX', '64GB', 2, 1499000, 'https://tokopedia.link/akswaok09Ob'),
('MSI PRO Z790-P WIFI', 'LGA 1700', 'm-ATX', '256GB', 4, 4565000, 'https://tokopedia.link/wJqRP0QbaPb');
