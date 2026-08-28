## Notation
API endpoints are descripbed by their access path, parameters and their response. 

Paths are relative, their prefix on your local development server is `/api/`, e.g  a valid full path will be something like `localhost:8080/api/posts/listPosts`

Parameters, if not said otherwise, are **GET parameters**, and shall be passed like that

Responses, if not said otherwise, are **JSON responses**, their structure is described via fields and their types.

If a JSON response contains an array, it is noted like `type[]`, for ex. `string[]` notes an array of strings.
