-- ========================================================================
-- Copyright (C) 2007-2009 Regis Houssin        <regis@dolibarr.fr>
-- Copyright (C) 2008      Laurent Destailleur  <eldy@users.sourceforge.net>
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
-- $Id: llx_c_barcode_type.sql,v 1.3 2011/08/03 01:25:34 eldy Exp $
-- ========================================================================

create table llx_c_barcode_type
(
  rowid    integer            AUTO_INCREMENT PRIMARY KEY,
  code     varchar(16)        NOT NULL,
  entity   integer  DEFAULT 1 NOT NULL,	-- multi company id
  libelle  varchar(50)        NOT NULL,
  coder    varchar(16)        NOT NULL,
  example  varchar(16)        NOT NULL
  
)ENGINE=innodb;
