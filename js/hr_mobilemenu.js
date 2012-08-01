
jQuery(document).ready(function($) {
  // Create the dropdown base
  $("<select id=\"main-menu-mobile\" />").appendTo("#region-menu .region-inner");
  $("#main-menu-mobile").mobileMenu({ ulsource: "#block-superfish-1 .content ul", maxLevel : 4 });
});
