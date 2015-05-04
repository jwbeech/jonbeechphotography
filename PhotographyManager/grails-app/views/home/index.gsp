<!DOCTYPE html>
<html>
<head>
	<meta name="layout" content="main"/>
	<title>Home - Jon Beech Photography</title>

	<g:if test="${!loggedIn}">
		<g:render template="templates/facebook"/>
	</g:if>

	<asset:javascript src="pages/home_index.js" />
</head>
<body>
</body>
</html>
