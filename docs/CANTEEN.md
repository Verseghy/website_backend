## Canteen API

### Common structures:

#### Menu

```
{
	"id": number
	"menu": string
	"type": number, 0-2
}
```

### Get menus by type
#### Path:
`GET  /canteen/getCanteenMenus?id={type}`
#### Paramters:
- type - the type of the canteen, 0-2
#### Returns:
```json
menu[]
```
Returns `400: Invalid request` if type is missing, or not in 0-2
Returns `404: Not found` if result is empty

***

### Get menus by week
#### Path:
`GET  /canteen/getCanteenByWeek?year={year}&week={week}`
#### Paramters:
- year
- week
#### Returns:
```json
{
	"id": number
	"date": string (date)
	"menus": menu[]
}[]
```
Returns `400: Invalid request`if any of the parameters is missing
Returns `404: Not found` if result is empty
