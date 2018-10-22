## Newsletter API

### Subscribe
#### Path:
`GET  /events/getEventsByMonth?year={year}&month={month}`
#### Paramters:
- year
- month
#### Returns:
```json
{
	"id": number
	"date_from": string(date)
	"date_to": string(date)
	"title": string
	"description": string
	"color": string(hex color)
}[]
```
Returns `400: Invalid request` if any of the parameters is missing, or if the month is outside the 1-12 range
Returns `404: Invalid request` if result is empty