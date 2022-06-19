CREATE TABLE `test`.`sheets` (
    `row` INT(12) NOT NULL ,
    `name` VARCHAR(255) NOT NULL ,
    `january` DECIMAL(10,2) NULL ,
    `february` DECIMAL(10,2) NULL ,
    `march` DECIMAL(10,2) NULL ,
    `april` DECIMAL(10,2) NULL ,
    `may` DECIMAL(10,2) NULL ,
    `june` DECIMAL(10,2) NULL ,
    `july` DECIMAL(10,2) NULL ,
    `august` DECIMAL(10,2) NULL ,
    `september` DECIMAL(10,2) NULL ,
    `october` DECIMAL(10,2) NULL ,
    `november` DECIMAL(10,2) NULL ,
    `december` DECIMAL(10,2) NULL ,
    `total` DECIMAL(10,2) NULL ,
    PRIMARY KEY (`row`)
) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_0900_ai_ci;