SELECT *, COUNT(1)
FROM (
   SELECT WEEK(create_time) AS wk, event_type, client_id
   FROM events_log
) x
GROUP BY wk, event_type, client_id
ORDER BY cliet_id, event_type, wk;
