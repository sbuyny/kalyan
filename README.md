GET /api/kalyannayas - list kalyannayas
POST /api/kalyannayas - add kalyannaya, parameters: name(string max 255)
GET /api/kalyannayas/:id - show kalyannaya
PUT /api/kalyannayas/:id - update kalyannaya, parameters: name(string max 255)
DELETE /api/kalyannayas/:id delete kalyannaya

GET /api/kalyans - list kalyans 
GET /api/kalyans/kalyannaya/:id - list kalyans by kalyannaya id
POST /api/kalyans - add kalyan, parameters: name(string max 255), trubok(integer max 10), kalyannaya_id(integer)
GET /api/kalyans/:id - show kalyan
PUT /api/kalyans/:id - update kalyan, parameters: name(string max 255), trubok(integer max 10), kalyannaya_id(integer)
DELETE /api/kalyans/:id - delete kalyan

GET /api/bookings - list bookings 
GET /api/bookings/kalyannaya/:id - list bookings by kalyannaya id
POST /api/bookings - add booking, parameters: name(string max 255), people(integer max 50), from(Y-m-d H:i:s), kalyannaya_id(integer)
GET /api/bookings/:id - show booking
GET /api/users - list users made bookings 
POST /api/search - list available kalyans, parameters: people(integer max 50), from(Y-m-d H:i:s), to(Y-m-d H:i:s)
