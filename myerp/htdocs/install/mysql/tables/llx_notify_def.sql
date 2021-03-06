-- ===================================================================
-- Copyright (C) 2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
--
-- This program is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 2 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program. If not, see <http://www.gnu.org/licenses/>.
--
-- $Id: llx_notify_def.sql,v 1.5 2011/08/03 01:25:28 eldy Exp $
-- ===================================================================

create table llx_notify_def
(
  rowid           integer AUTO_INCREMENT PRIMARY KEY,
  tms             timestamp,
  datec           date,             -- date de creation
  fk_action       integer NOT NULL,
  fk_soc          integer NOT NULL,
  fk_contact      integer,
  fk_user		  integer,
  type            varchar(16) DEFAULT 'email'
)ENGINE=innodb;
