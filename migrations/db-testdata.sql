/* ============================ CMSGears Subscriptions ====================================== */

SET FOREIGN_KEY_CHECKS=0;

--
-- Subscription module roles and permissions
--

INSERT INTO `cmg_core_role` (`createdBy`,`modifiedBy`,`name`,`slug`,`homeUrl`,`type`,`icon`,`description`,`createdAt`,`modifiedAt`) VALUES 
	(1,1,'Subscription Manager','subscription-manager','dashboard','system',NULL,'The role Subscription Manager is limited to manage subscriptions from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54');

SELECT @rolesadmin := `id` FROM cmg_core_role WHERE slug = 'super-admin';
SELECT @roleadmin := `id` FROM cmg_core_role WHERE slug = 'admin';
SELECT @rolesubs := `id` FROM cmg_core_role WHERE slug = 'subscription-manager';

INSERT INTO `cmg_core_permission` (`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`icon`,`description`,`createdAt`,`modifiedAt`) VALUES 
	(1,1,'Subscription','subscription','system',NULL,'The permission subscription is to manage subscriptions and subscribers from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54');

SELECT @permadmin := `id` FROM cmg_core_permission WHERE slug = 'admin';
SELECT @permuser := `id` FROM cmg_core_permission WHERE slug = 'user';
SELECT @permsubs := `id` FROM cmg_core_permission WHERE slug = 'subscription';

INSERT INTO `cmg_core_role_permission` VALUES 
	(@rolesadmin,@permsubs),
	(@roleadmin,@permsubs),
	(@rolesubs,@permadmin),(@rolesubs,@permuser),(@rolesubs,@permsubs);

SET FOREIGN_KEY_CHECKS=1;