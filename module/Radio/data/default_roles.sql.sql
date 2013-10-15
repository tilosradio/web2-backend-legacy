# roles have all the rights of their parent, so 'guest' is the role with the least permissions
INSERT INTO `role` (`id`, `parent_role_id`, `name`) VALUES
(1, NULL, 'guest'),
(2, 1, 'user'),
(3, 2, 'author'),
(4, 3, 'admin');