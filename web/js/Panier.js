//Création d'un cookie'
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();

    // règle le pb des caractères interdits
    if ('btoa' in window) {
        cvalue = btoa(cvalue);
    }

    document.cookie = cname + "=" + cvalue + "; " + expires + ';path=/';
}
function getLogin() {
    var login = document.getElementById("idlogin").innerHTML;
    return login;
}
// sauvegarde du panier
function saveCart(inCartItemsNum, cartArticles) {
    setCookie('inCartItemsNum' + getLogin(), inCartItemsNum, 5);
    setCookie('cartArticles' + getLogin(), JSON.stringify(cartArticles), 5);
}

// récupération des données
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');

    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c[0] == ' ') {
            c = c.substring(1);
        }

        if (c.indexOf(name) != -1) {
            if ('btoa' in window) {
                return atob(c.substring(name.length, c.length));
            }
            else {
                return c.substring(name.length, c.length);
            }
        }
    }
    return false;
}

// variables pour stocker le nombre d'articles et leurs noms
var inCartItemsNum;
var cartArticles;


// affiche/cache les éléments du panier selon s'il contient des produits
function cartEmptyToggle() {
    if (inCartItemsNum > 0) {
        $('#empty-cart-msg').hide();
        $('#full-cart-msg').show();
        document.getElementById("opanier").className = "btn btn-block btn-embossed btn-primary btn-orange";
        document.getElementById("header-panier").className = "modal-header-full modal-header";
    }

    else {
        $('#empty-cart-msg').show();
        $('#full-cart-msg').hide();
        document.getElementById("opanier").className = "btn btn-block btn-embossed btn-primary btn-success";
        document.getElementById("header-panier").className = "modal-header-empty modal-header";
    }
}
function deleteItem() {
    // suppression d'un article
    $('.delete-item').click(function () {
        var $this = $(this);
        var qt = parseInt($this.prevAll('.qt').html());
        var id = $this.parent().parent().attr('data-id');
        var arrayId = 0;
        var price;

        // maj qt
        if (inCartItemsNum <= 1) {
            inCartItemsNum = 0;
        }
        else {
            inCartItemsNum -= 1;
        }

        $('#in-cart-items-num').html(inCartItemsNum);

        // supprime l'item du DOM
        $this.parent().parent().hide(600);
        $('#' + id).remove();

        cartArticles.forEach(function (v) {
            // on récupère l'id de l'article dans l'array
            if (v.id == id) {
                // on met à jour le sous total et retire l'article de l'array
                subTotal -= parseFloat(v.price.replace(',', '.'));
                cartArticles.splice(arrayId, 1);

                return false;
            }
            arrayId++;
        });

        $('.subtotal').html(subTotal.toFixed(2).replace('.', ','));
        saveCart(inCartItemsNum, cartArticles);
        cartEmptyToggle();
    });
}
// fonction qui met à jour les données
function refreshCart() {
    var items = '';
    inCartItemsNum = parseInt(getCookie('inCartItemsNum' + getLogin()) ? getCookie('inCartItemsNum' + getLogin()) : 0);
    cartArticles = getCookie('cartArticles' + getLogin()) ? JSON.parse(getCookie('cartArticles' + getLogin())) : [];
    subTotal = 0;
    cartArticles.forEach(function (v) {
        subTotal = parseFloat(subTotal);
        console.log(subTotal);
        items += '<tr data-id="' + v.id + '">\
             <td><a href="' + v.url + '">' + v.name + '</a></td>\
             <td>' + v.price + '€</td>\
             <td><a class="delete-item"><button class="btn btn-danger btn-embossed"><span class="glyphicon glyphicon-trash"></span></button></a></td></tr>';
        subTotal += v.price.replace(',', '.') * v.qt;
    });
    console.log(subTotal);
    $('#cart-tablebody').empty().html(items);
    $('.subtotal').html(subTotal.toFixed(2).replace('.', ','));
}
$("#opanier").click(function () {
    if ($('#in-cart-items-num').innerHTML != 0)
        $('#Panier').modal('toggle');
});

// récupère les informations stockées dans les cookies
inCartItemsNum = parseInt(getCookie('inCartItemsNum' + getLogin()) ? getCookie('inCartItemsNum' + getLogin()) : 0);
cartArticles = getCookie('cartArticles' + getLogin()) ? JSON.parse(getCookie('cartArticles' + getLogin())) : [];

cartEmptyToggle();

// affiche le nombre d'article du panier dans le widget
$('#in-cart-items-num').html(inCartItemsNum);

// hydrate le panier
var items = '';
cartArticles.forEach(function (v) {
    items += '<li id="' + v.id + '"><a href="' + v.url + '">' + v.name + '<br><small>Quantité : <span class="qt">' + v.qt + '</span></small></a></li>';
});

$('#cart-dropdown').prepend(items);

// click bouton ajout panier
$('.add-to-cart').click(function () {
    cartArticles = getCookie('cartArticles' + getLogin()) ? JSON.parse(getCookie('cartArticles' + getLogin())) : [];

    // récupération des infos du produit
    var $this = $(this);
    var id = $this.attr('data-id');
    var name = $this.attr('data-name');
    var price = $this.attr('data-price');
    var url = $this.attr('data-url');
    var qt = 1;

    var newArticle = true;
    // vérifie si l'article est pas déjà dans le panier
    cartArticles.forEach(function (v) {
        // si l'article est déjà présent
        if (v.id == id) {
            newArticle = false;
        }
    });

    // s'il est nouveau, on l'ajoute
    if (newArticle == true) {
        $('#cart-dropdown').prepend('<li id="' + id + '"><a href="' + url + '">' + name + '<br><small>Quantité : <span class="qt">' + qt + '</span></small></a></li>');

        // mise à jour du nombre de produit dans le widget
        inCartItemsNum += qt;
        $('#in-cart-items-num').html(inCartItemsNum);

        cartArticles.push({
            id: id,
            name: name,
            price: price,
            qt: 1,
            url: url
        });
    }

    // sauvegarde le panier
    saveCart(inCartItemsNum, cartArticles);

    // affiche le contenu du panier si c'est le premier article
    cartEmptyToggle();
    refreshCart();
    deleteItem();
});

var items = '';
var subTotal = 0;
var total;

/* on parcourt notre array et on créé les lignes du tableau pour nos articles :
 * - Le nom de l'article (lien cliquable qui mène à la fiche produit)
 * - son prix
 * - la dernière colonne permet de modifier la quantité et de supprimer l'article
 *
 * On met aussi à jour le sous total et le poids total de la commande
 */
cartArticles.forEach(function (v) {
    subTotal = parseFloat(subTotal);
    items += '<tr data-id="' + v.id + '">\
             <td><a href="' + v.url + '">' + v.name + '</a></td>\
             <td>' + v.price + '€</td>\
             <td><a class="delete-item"><button class="btn btn-danger btn-embossed"><span class="glyphicon glyphicon-trash"></span></button></a></td></tr>';
    subTotal += v.price.replace(',', '.') * v.qt;
});

// On insère le contenu du tableau et le sous total
$('#cart-tablebody').empty().html(items);
$('.subtotal').html(subTotal.toFixed(2).replace('.', ','));

// suppression d'un article
$('.delete-item').click(function () {
    var $this = $(this);
    var qt = parseInt($this.prevAll('.qt').html());
    var id = $this.parent().parent().attr('data-id');
    var arrayId = 0;
    var price;

    // maj qt
    if (inCartItemsNum <= 1) {
        inCartItemsNum = 0;
    }
    else {
        inCartItemsNum -= 1;
    }

    $('#in-cart-items-num').html(inCartItemsNum);

    // supprime l'item du DOM
    $this.parent().parent().hide(600);
    $('#' + id).remove();

    cartArticles.forEach(function (v) {
        // on récupère l'id de l'article dans l'array
        if (v.id == id) {
            // on met à jour le sous total et retire l'article de l'array
            subTotal -= parseFloat(v.price.replace(',', '.'));
            cartArticles.splice(arrayId, 1);

            return false;
        }
        arrayId++;
    });

    $('.subtotal').html(subTotal.toFixed(2).replace('.', ','));
    saveCart(inCartItemsNum, cartArticles);
    cartEmptyToggle();
});

