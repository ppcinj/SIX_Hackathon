select import.days, kanton.name, t1.summe
from import as base,
(select sum(spend*numTrx) as summe from import where import.days = base.days) as t1
join kanton on import.kanton = kanton.id