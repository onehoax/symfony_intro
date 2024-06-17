SELECT
  name
FROM
  USER u
  LEFT JOIN ORDER o ON u.order_id = o.id
;
