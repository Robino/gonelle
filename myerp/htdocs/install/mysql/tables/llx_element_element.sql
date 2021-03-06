-- ============================================================================
-- Copyright (C) 2008-2011	Laurent Destailleur	<eldy@users.sourceforge.net>
-- Copyright (C) 2011		Regis Houssin		<regis@dolibarr.fr>
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
-- $Id: llx_element_element.sql,v 1.4 2011/08/03 01:25:28 eldy Exp $
-- ============================================================================
-- Table used for relations between elements of different types:
-- invoice-propal, propal-order, etc...
-- ============================================================================

create table llx_element_element
(
  rowid           	integer AUTO_INCREMENT PRIMARY KEY,  
  fk_source			integer NOT NULL,
  sourcetype		varchar(32) NOT NULL,
  fk_target			integer NOT NULL,
  targettype		varchar(32) NOT NULL
) ENGINE=innodb;

