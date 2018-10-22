## Newsletter API

### Subscribe
#### Path:
`GET  /newsletter/subscribe?email={email}&mldata={mldata}`
#### Paramters:
- email - the email to subscribe
- mldata - the machine learning data associated with the email
#### Returns:
```json
{}
```
Returns `400: Invalid request` if any of the parameters is missing
Returns `409: Conflict` if the email is already subscribed
Returns `500: Internal server error` on error
***
### Unsubscribe
#### Path:
`GET  /newsletter/unsubscribe?email={email}&token={token}`
#### Paramters:
- email - the email to unsubscribe
- token - the secret verification token of the email
#### Returns:
```json
{}
```
Returns `400: Invalid request` if any of the parameters is missing
Returns `400: Invalid request` if the email is not in the database
Returns `401: Unauthorized` if the token does not match
Returns `500: Internal server error` on error