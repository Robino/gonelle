-- ===================================================================
-- Copyright (C) 2009-2011 Regis Houssin  <regis@dolibarr.fr>
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
-- $Id: llx_propaldet.key.sql,v 1.3 2011/08/03 01:25:37 eldy Exp $
-- ===================================================================



ALTER TABLE llx_propaldet ADD INDEX idx_propaldet_fk_propal (fk_propal);
ALTER TABLE llx_propaldet ADD INDEX idx_propaldet_fk_product (fk_product);

ALTER TABLE llx_propaldet ADD CONSTRAINT fk_propaldet_fk_propal FOREIGN KEY (fk_propal) REFERENCES llx_propal (rowid);
