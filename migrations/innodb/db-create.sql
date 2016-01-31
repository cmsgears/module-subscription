/* ============================ CMSGears Subscriptions ====================================== */

--
-- Table structure for table `cmg_subscription`
--

DROP TABLE IF EXISTS `cmg_subscription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_subscription` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `planId` bigint(20) NOT NULL,
  `subscriberId` bigint(20) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_subscription_1` (`planId`),
  KEY `fk_subscription_2` (`subscriberId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


SET FOREIGN_KEY_CHECKS=0;

--
-- Constraints for table `cmg_subscription`
--
ALTER TABLE `cmg_subscription` 
	ADD CONSTRAINT `fk_subscription_1` FOREIGN KEY (`planId`) REFERENCES `cmg_core_object` (`id`),
	ADD CONSTRAINT `fk_subscription_2` FOREIGN KEY (`subscriberId`) REFERENCES `cmg_core_user` (`id`);

SET FOREIGN_KEY_CHECKS=1;