function setLanguage(id)
{
	$('form#setLanguage input#id_lang').val(id);
	$('form#setLanguage').submit();
}
function setCurrency(id)
{
	$('form#setCurrency input#id_currency').val(id);
	$('form#setCurrency').submit();
}