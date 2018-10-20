##Posts API

###Common structures:

####Label

```
{
	"id": number
	"name": string
	"color": string, hex color
}
```

####Author
```
{
	"id": number
	"name": string
	"description": string
	"image": url
}
```
####Post
```json
{
	"id": number
	"title": string
	"color": hex color string
	"featured": number 0/1
	"description": string
	"index_image": url
	"images": url[]
	"date": date
	"author": author
	"labels": label[]
	"content": string, HTML
}
```

###List Posts
####Path:
`GET  /posts/listPosts` - defaults to first page
`GET  /posts/listPosts?page={pagenum}`

####Paramters:
- pagenum - the number of the page to show
####Returns:
```json
post[]
```
Sorted by date(descending), 20 posts/page
Cacheable
Returns `404: Not found` on empty

***

###Get post
####Path:
`GET  /posts/getPost?id={id}`

####Paramters:
- id - the id of the post to get
####Returns:
```json
post
```
Cacheable
Returns `404: Not found` on empty
Returns `400: Invalid request` if id is missing

***

###Get posts **by label**
####Path:
`GET  /posts/getPostsByLabel?id={labelid}&page={pagenum}`
`GET  /posts/getPostsByLabel?id={labelid}`
####Paramters:
- id - the id of the label to filter by
- page - the page to show
####Returns:
```json
post[]
```
Sorted by date(descending), 20 posts/page
Cacheable
Returns `404: Not found` on empty
Returns `400: Invalid request` if id is missing
***

###Get posts **by author**
####Path:
`GET  /posts/getPostsByAuthor?id={labelid}&page={pagenum}`
`GET  /posts/getPostsByAuthor?id={labelid}`
####Paramters:
- id - the id of the author to filter by
- page - the page to show
####Returns:
```json
post[]
```
Sorted by date(descending), 20 posts/page
Cacheable
Returns `404: Not found` on empty
Returns `400: Invalid request` if id is missing

###Get posts **by search term**
####Path:
`GET  /posts/search?term={tern}`
####Paramters:
- term - the search term to filter by. Searches in title, description and content
- page - the page to show
####Returns:
```json
post[]
```
Sorted by date(descending), 20 posts/page
Cacheable
Returns `404: Not found` on empty
Returns `400: Invalid request` if term is missing