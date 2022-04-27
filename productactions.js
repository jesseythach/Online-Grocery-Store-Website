//document.head.insertAdjacentHTML('afterbegin', '<script type="module" src="itemValidity.js"></script>')

let urlSearch = new URLSearchParams(window.location.search);
let currentID = urlSearch.get('id');
let validModule;
let checkValidity = function () {
};

let isCheckCorrect = true;

function showDesc() {
    const x = document.getElementById("more-description");
    if (x.style.transform === "scaleY(0)") {
        x.style.transform = "scaleY(1)";
    } else {
        x.style.transform = "scaleY(0)";
    }
}


function updateProductPrice(productID, input, outputID, price) {

    let cartButton = document.getElementById('add-to-cart');

    isCheckCorrect = checkValidity(input);


    if (isCheckCorrect) {
        input.style.outlineColor = 'black';
        document.getElementById(outputID).innerHTML = parseFloat("" + input.value * price).toFixed(2) + "$";
        let productContainer = {};


        // productContainer[input.id] = {, }
        productContainer[outputID] = { innerHTML: parseFloat("" + input.value * price).toFixed(2) + "$" };
        productContainer[input.id] = { value: input.value };


        // localStorage.setItem(window.location.pathname, JSON.stringify(productContainer))
        localStorage.setItem("id:" + currentID, JSON.stringify(productContainer));

    } else {
        input.style.outlineColor = 'red';
    }


}


function addToCart(id, amount, step = 1) {
    let serverSend = new XMLHttpRequest();


    serverSend.onload = function (evt) {


        console.log(JSON.parse(serverSend.response));

    };

    serverSend.open("post", "./addCart.php");
    serverSend.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    console.log("amount: " + amount);


    if (isCheckCorrect) {
        serverSend.send("id=" + id + "&amount=" + amount);

        showCartComplete('darkgreen', 'Item Successfully added to cart.');
    } else {
        showCartComplete('red', 'Not enough in stock or invalid quantity, try again.');
    }
}


window.addEventListener('load', function () {

    document.head.insertAdjacentHTML('afterbegin', '<script type="module" src="itemValidity.js"></script>');
    let input = document.getElementById('amountProduct');
    import('./itemValidity.js').then(function (e) {
        checkValidity = e.checkValidity;
        isCheckCorrect = checkValidity(input);
        let prod = JSON.parse(localStorage.getItem("id:" + currentID));
        for (let i in prod) {
            let element = document.getElementById(i);
            for (let insideVal in prod[i]) {
                element[insideVal] = prod[i][insideVal];
            }

        }
    });


});

let isTaken = false;


function showCartComplete(color, text) {
    if (!isTaken) {
        isTaken = true;
        let host = window.location.host;
        let buttonElement = document.getElementsByClassName('actionbutton').item(1);
        let cartSuccessContainerElement = document.createElement('div');
        cartSuccessContainerElement.id = 'add-to-cart-success';
        cartSuccessContainerElement.style.display = 'block';
        cartSuccessContainerElement.style.position = 'fixed';

        cartSuccessContainerElement.style.width = '100%';
        cartSuccessContainerElement.style.left = '0';

        cartSuccessContainerElement.style.bottom = '-30px';
        cartSuccessContainerElement.style.height = 30 + 'px';
        cartSuccessContainerElement.style.backgroundColor = color;


        let cartSuccessLinkContainerElement = document.createElement('a');
        let cartSuccessTextElement = document.createElement('p');
        cartSuccessTextElement.style.position = 'fixed';
        cartSuccessTextElement.style.height = '30px';
        cartSuccessTextElement.style.width = '100vw';
        cartSuccessTextElement.style.left = '0';
        cartSuccessTextElement.style.bottom = '-50px';
        cartSuccessTextElement.style.textAlign = "center";
        cartSuccessTextElement.id = 'bar-text';
        cartSuccessTextElement.innerHTML = text;


        if (host.match('localhost')) {
            cartSuccessLinkContainerElement.href = 'http://' + host + '/Online-Grocery_Web-Programming/cart.php';
        } else {
            cartSuccessLinkContainerElement.href = "/cart.php";
        }
        cartSuccessLinkContainerElement.className = 'font';

        buttonElement.insertAdjacentElement('afterend', cartSuccessContainerElement);
        cartSuccessContainerElement.insertAdjacentElement('afterbegin', cartSuccessLinkContainerElement);

        cartSuccessLinkContainerElement.insertAdjacentElement('afterbegin', cartSuccessTextElement);

        cartSuccessTextElement.textAlign = 'center';
        cartSuccessTextElement.style.textDecoration = 'none';
        cartSuccessTextElement.style.color = 'white';

        let counter = 0;


        let frame = setInterval(function () {


            if (counter > 28) {
                let isReady = false;

                //pause before rolling back
                setTimeout(function () {
                    isReady = true;
                }, 1500);

                //roll back off viewport
                let frame2 = setInterval(function () {
                    if (isReady) {

                        if (counter <= 0) {


                            cartSuccessContainerElement.remove();
                            isTaken = false;

                            clearInterval(frame2);
                        }

                        cartSuccessContainerElement.style.bottom = counter - 30 + "px";
                        cartSuccessTextElement.style.bottom = counter - 50 + 'px';


                        counter--;


                    }

                }, 10);


                clearInterval(frame);
            }
            counter++;
            cartSuccessTextElement.style.bottom = -50 + counter + 'px';
            cartSuccessContainerElement.style.bottom = -30 + counter + 'px';


        }, 10);
    }

}
