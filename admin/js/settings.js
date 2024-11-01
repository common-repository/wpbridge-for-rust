(function($){
"use strict"
$("#wpbridge-for-rust-settings-tabs").tabs();


/**
 * Pro lost subscription page
 */
const wpbridge_pro_lost_subscription_submenu_item = $('.wp-submenu-wrap > li > a[href$="?page=wpbridge-pro-lost-license"]');
if(wpbridge_pro_lost_subscription_submenu_item.length) {
    wpbridge_pro_lost_subscription_submenu_item.parent().remove()
}


/**
 * Pro payment Success page
 */

const wpbridge_pro_success_submenu_item = $('.wp-submenu-wrap > li > a[href$="?page=wpbridge-pro-success"]');
if(wpbridge_pro_success_submenu_item.length) {
    wpbridge_pro_success_submenu_item.parent().remove()
}

const wpbridge_for_rust_textarea_subscription = $('.wpbridge-for-rust-textarea-subscription');
const wpbridge_for_rust_textarea_subscription_license_name = wpbridge_for_rust_textarea_subscription.data('license')

if(wpbridge_for_rust_textarea_subscription.length > 0 && wpbridge_for_rust_textarea_subscription_license_name) {
    
        
        
        const blob = new Blob([wpbridge_for_rust_textarea_subscription.val()],
                { type: "text/plain;charset=utf-8" }, `${wpbridge_for_rust_textarea_subscription_license_name}.txt`);
        const link=window.URL.createObjectURL(blob);

        let a = document.getElementById("wpbridge-download-subscription-info-link")
        if(!a) {
            a = document.createElement("a")
            a.id = "wpbridge-download-subscription-info-link"
            a.style = "display: none";
            document.body.appendChild(a);
            a.href = link;
            a.download = `${encodeURIComponent(USERDATA.site_url)}${wpbridge_for_rust_textarea_subscription_license_name}.LICENSE`
        }
        a.click()
    wpbridge_for_rust_textarea_subscription.focus(function() {
        copyToClipBoard(wpbridge_for_rust_textarea_subscription.val())
        this.select()
    }).mouseup(function() {
        return false;
    })
}
/**
 * Pro products 
 */

const wpbridge_for_rust_lost_subscription_link = $("#wpbridge-for-rust-lost-subscription-link");
if(wpbridge_for_rust_lost_subscription_link.length) {
    wpbridge_for_rust_lost_subscription_link.on("click", function(e) {
        e.preventDefault()
        
        setModalContent($,`subscription/lostsubscription.modal.php`,() => {
            const wpbridge_for_rust_lost_subscription_file_upload = $("#wpbridge-for-rust-lost-subscription-file-upload");
            const wpbridge_for_rust_lost_subscription_file_upload_status_elem = $("#wpbridge-for-rust-lost-subscription-file-upload-status-elem")
            wpbridge_for_rust_lost_subscription_file_upload.on("change", function() {
                wpbridge_for_rust_lost_subscription_file_upload_status_elem.html("")
                const fileList = this.files;
                if(fileList.length == 1) {
                    const file = fileList[0]
                    const fileReader = new FileReader();
                    fileReader.addEventListener("load", function() {
                        if(fileReader.result.trim().startsWith("Key=\"")) {
                            const fileContentsSplit = fileReader.result.trim().split("\"")
                            if(fileContentsSplit.length !== 5) {
                                wpbridge_for_rust_lost_subscription_file_upload_status_elem.html("Failed to reactivate LICENSE file.")
                                return
                            }
                            const probablyLicense = {
                                license_key: fileContentsSplit[1],
                                site_url: fileContentsSplit[3]
                            }
                            console.log(probablyLicense)
                            window.location.href = `${USERDATA.admin_url}admin.php?page=wpbridge-pro-lost-license&license_key=${probablyLicense.license_key}&site_url=${probablyLicense.site_url}`
                        } else {
                            wpbridge_for_rust_lost_subscription_file_upload_status_elem.html("The file is not a valid WPBridge LICENSE file.");
                        }
                    },false)
                    fileReader.readAsText(file)
                }
            })


            openModal($)
        })
        
    })
}

const wpbridge_for_rust_pro_products = $("#wpbridge-for-rust-pro-products");
if(wpbridge_for_rust_pro_products.length > 0) {
    fetchProducs();
}

function fetchProducs()
{
    fetch(`${WPBRIDGEPRODATA.endpoints.payment}products`)
    .then(res => {
        if(res.ok) return res.json()
        wpbridge_for_rust_pro_products.html(`
            <h3>There was an error fetching pro products. Please try later.</h3>
        `)
    })
    .then(products => {
        
        if(products.length > 0) {
            wpbridge_for_rust_pro_products.html("")
            wpbridge_for_rust_pro_products.append(`
                <div class="wpbridge-for-rust-pro-product wpbridge-for-rust-pro-product-free">
                    <div class="wpbridge-for-rust-product-header-wrapper">
                        <h3>WPBridge Free</h3>
                    </div>
                    <h1 title="Free">Free</h1>
                    <ul>
                        <li><img src="${USERDATA.site_url}/wp-content/plugins/wpbridge-for-rust/admin/img/check.svg">Shortcodes</li>
                        <li><img src="${USERDATA.site_url}/wp-content/plugins/wpbridge-for-rust/admin/img/check.svg">Free grade support priority</li>
                    </ul>
                    <button class="wpbridge-for-rust-pro-product-buy-btn" disabled>Installed</button>
                </div>
            `)
            for (let i = 0; i < products.length; i++) {
                const product = products[i]
                if(product.id && product.name && product.name_pretty && product.description && product.key_features && product.price_in_cents) {

                    const price_usd = product.price_in_cents / 100
                    const name_short = product.name_pretty.split(" ")[1]

                    const key_features = product.key_features.map(key_feature => {
                        return `<li><img src="${USERDATA.site_url}/wp-content/plugins/wpbridge-for-rust/admin/img/check.svg">${key_feature}</li>`
                    })

                    wpbridge_for_rust_pro_products.append(`
                        <div id="${product.name}" class="wpbridge-for-rust-pro-product">
                            <div class="wpbridge-for-rust-product-header-wrapper">
                                <h3>${product.name_pretty}</h3>
                            </div>
                            <h1 title="${price_usd}$ USD">${price_usd}$</h1>
                            <ul>${key_features.join("")}</ul>
                            <button class="wpbridge-for-rust-pro-product-buy-btn" data-id="${product.id}">Buy ${name_short}</button>
                        </div>
                    `)
                }
            }
            const wpbridge_for_rust_pro_product_buy_btn = $('.wpbridge-for-rust-pro-product-buy-btn');
            wpbridge_for_rust_pro_product_buy_btn.on('click', function() {
                const product_id = $(this).data('id')
                if(product_id) {
                    buyProduct(product_id)
                }
            })
        }
    })
    .catch((err) => {
        wpbridge_for_rust_pro_products.html(`
            <h3>There was an error fetching pro products. Please try later.</h3>
        `)
        console.log(err)
    })
}

function buyProduct(product_id) {
    const postData = {
        id:product_id,
        site: {
            ip: USERDATA.ip,
            url: USERDATA.site_url,
            current_page: window.location.href
        },
        customer : {
            name: USERDATA.user_nicename,
            email: USERDATA.user_email,

        }
    }
    fetch(`${WPBRIDGEPRODATA.endpoints.payment}buy`, {
        method: 'POST',
        headers: {"Content-Type": "application/json"},
        body:JSON.stringify(postData)
    })
    .then(res => {
        if(res.ok) return res.json()
        console.log(res.statusText)
    })
    .then(json => {
        if(json.checkout_url) {
            window.location = json.checkout_url
        } else {
            wpbridge_for_rust_pro_products.html(`
                <h3>There was an error creating checkout session. Please try later.</h3>
            `)
            setTimeout(() => {
                fetchProducs()
            },3000)
        }
    })
}

/**
 * Status page
 */

 const wpbridge_for_rust_tab_btn = $('.wpbridge-for-rust-tab-btn');
 wpbridge_for_rust_tab_btn.on("click", function(e) {
     e.preventDefault();
     if($(this).data('parent') == "wpbridge-for-rust-setup-tab") {
         $("#wpbridge-for-rust-settings-tabs").tabs({ active: 1 });
         $("#wpbridge_secret_generate_button").focus();
     }
 });

/**
 * RustMap
 */

const wpbridge_for_rust_rustmaps_tabs = $("#wpbridge-for-rust-rustmaps-tabs");
wpbridge_for_rust_rustmaps_tabs.tabs();


const wpbridge_for_rust_generate_rust_map_btn = $('#wpbridge-for-rust-generate-rust-map-btn');
wpbridge_for_rust_generate_rust_map_btn.on('click', function(e) {
    e.preventDefault();
    if(USERDATA && USERDATA.rustmaps && USERDATA.rustmaps.api_key && SERVERSETTINGS && SERVERSETTINGS.seed && SERVERSETTINGS.worldsize) {
        let successfullyFetchedMapData = false;
        setModalContent($,`rustmaps/generating.modal.php`,() => {
            const wpbridge_for_rust_generating_modal_status_textElem = $('#wpbridge-for-rust-generating-modal-status-text');
            const wpbridge_for_rust_generating_modal_loading_gif = $('#wpbridge-for-rust-generating-modal-loading-gif');
            wpbridge_for_rust_generating_modal_status_textElem.text(`Initializing map width seed ${SERVERSETTINGS.seed} and size ${SERVERSETTINGS.worldsize}...`);
            wpbridge_for_rust_generating_modal_loading_gif.attr('src', `${USERDATA.site_url}/wp-content/plugins/wpbridge-for-rust/admin/img/generating_skull.gif`);
            openModal($,true);
            fetchRustMap(SERVERSETTINGS.seed,SERVERSETTINGS.worldsize,(result) => {
                if(!result.id) wpbridge_for_rust_generating_modal_status_textElem.text(`Ops..! Rustmaps encountered an error. Please try again in a few minutes.`)
                wpbridge_for_rust_generating_modal_status_textElem.text(`Map successfully generated!`)

                if(result.monuments) {
                    for (const monument of result.monuments) {
                        if(monument.prefab) delete(monument.prefab)
                    }
                }
                $.ajax({
                    type:   'POST',
                    url:    ajaxurl,
                    data:   {
                        action    : 'wpbridge_rustmapapidata',
                        rustmapapidata : result,
                        wpbridge_rustmapapigeneratedfilename : result.id
                    },
                    dataType: 'json',
                    
                }).done(function( json ) {
                    if(json.success) {
                        successfullyFetchedMapData = true;
                        wpbridge_for_rust_generating_modal_status_textElem.text(`Map data successfully saved in database!`)
                    } else {
                        wpbridge_for_rust_generating_modal_status_textElem.text(`Ops..! There was an issue saving map data! (done)`)
                    }
                }).fail(function(err) {
                    wpbridge_for_rust_generating_modal_status_textElem.text(`Ops..! There was an issue saving map data! (fail)`)
                    console.log( "The Ajax call failed." );
                    console.log( err );
                }).always(function() {
                    setTimeout(function() {
                        closeModal($)
                        if(successfullyFetchedMapData) {
                            if(window.location.href.indexOf('#wpbridge-for-rust-rustmaps-tab') == -1) window.location.href += '#wpbridge-for-rust-rustmaps-tab'
                            window.location.reload()
                        }
                    },2000)
                })
            },'GET',wpbridge_for_rust_generating_modal_status_textElem)
        })
        
    }
})

/**
 * Statistics tab
 */
const wpbridge_for_rust_statistics_tabs = $("#wpbridge-for-rust-statistics-tabs");
wpbridge_for_rust_statistics_tabs.tabs();

const copyDataElem = $(".copyData");
copyDataElem.on("click", function(e) {
    e.preventDefault();
    var textToCopy = $(this).data('copy');
    if(textToCopy) {
        const copiedMessageElem = document.createElement("span");
        copiedMessageElem.className = textToCopy+"copyElem";
        if(copyToClipBoard(textToCopy)) {
            copiedMessageElem.textContent = ` [${textToCopy}] copied to clipboard`;
        } else {
            copiedMessageElem.textContent = `Could not copy to clipboard.`;
        }
        $(this).parent().append(copiedMessageElem);
        setTimeout(function() {
            $(copiedMessageElem).fadeOut(500, function() {
                $(copiedMessageElem).remove();
            });
        },1000);
    }
});

/**
 * Secret
 */
const wpbridge_secret_generate_button = $("#wpbridge_secret_generate_button")
const wpbridge_secret_field = $("#wpbridge_secret_field")
wpbridge_secret_generate_button.on('click', (e) => {
    e.preventDefault()
    wpbridge_secret_field.val(generateSecret(20))
})

/**
 * Database
 */
const wpbridge_database_purge_players_button = $("#wpbridge_database_purge_players_button");
wpbridge_database_purge_players_button.on('click', (e) => {
    e.preventDefault();
    if(confirm("Are you sure you want to clear all player data?"))
    {
        window.location.replace("?page=wpbridge-purge-statistics-database");
    }
});

const wpbridge_clear_statistics_elem = $(".wpbridge_clear_statistics_elem");
wpbridge_clear_statistics_elem.on('click', (e) => {
    e.preventDefault();
    if(confirm("Are you sure you want to clear all player data?"))
    {
        window.location.replace("?page=wpbridge-purge-statistics-database");
    }
});

/* MODAL */



})(jQuery)

function setModalContent($,modalContentUrl,callback) {
    const wpbridge_modal = $('#wpbridge-for-rust-modal');
    $.get(`${USERDATA.modal_url}${modalContentUrl}`, function(data) {
        wpbridge_modal.find('.wpbridge-modal-body').html(data)
        callback()
    })
}

function closeModal($) {
    const wpbridge_modal_close_btn = $(".wpbridge-modal-close")
    wpbridge_modal_close_btn.click()
}

function openModal($,preventClose = false) {
    const wpbridge_modal = $("#wpbridge-for-rust-modal")
    const wpbridge_modal_close_btn = $(".wpbridge-modal-close")
    wpbridge_modal.fadeIn(200)


    wpbridge_modal_close_btn.unbind('click').on('click', function() {
        wpbridge_modal.fadeOut(200)
    })

    if(!preventClose) {
        $(window).unbind('click').on('click', function(e) {
            console.log(e.target)
            if(e.target == wpbridge_modal[0]) {
                wpbridge_modal.fadeOut(200)
            }
        })
    }

    // window.onclick = function(event) {
    //     console.log(event.target)
    //     if (event.target == modal) {
    //         modal.css('display','none');
    //     }
    // }
}

function generateSecret(n) {
    var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_*!#$.,-';
    var token = '';
    for(var i = 0; i < n; i++) {
        token += chars[Math.floor(Math.random() * chars.length)];
    }
    return token;
}

function copyToClipBoard(text) {
    navigator.clipboard.writeText(text);
    return true;
}
function fetchRustMap(seed,size,callback,method = false, statusElem = false) {
    if(!method) method = 'POST';
    console.log(`https://rustmaps.com/api/v2/maps/${seed}/${size}`)
    fetch(`https://rustmaps.com/api/v2/maps/${seed}/${size}?staging=false&barren=false`, {
        method:method,
        headers: {
            'X-API-Key':USERDATA.rustmaps.api_key
        }
    })
    .then(data => data.json())
    .then(json => {
        if(json.status && json.status !== 200) {
            console.log(`status: ${json.status}`)
            if(statusElem) {
                statusElem.text(`Rustmaps is working on it..\nCurrent state: ${json.status}`)
            }
            console.log(`Trying to POST to generate`)
            fetchRustMap(seed,size,callback,'POST',statusElem)
            return
        }
        if(json.currentState) {
            if(statusElem) {
                statusElem.text(`Rustmaps is working on it..\nCurrent state: ${json.currentState}`)
            }
            console.log(`current state: ${json.currentState}. Trying in a bit`);
            setTimeout(function() {
                fetchRustMap(seed,size,callback,'GET',statusElem)
                return
            },2000)
        } else {
            if(json.seed && json.seed == seed) return callback(json)
            if(json.mapId) {
                if(statusElem) {
                    statusElem.text(`Rustmaps is working on it..`)
                }
                console.log(`Probably generating. Trying to GET in a bit`)
                setTimeout(function() {
                    fetchRustMap(seed,size,callback,'GET',statusElem)
                    return
                },3000)
            }
        }
    })
    .catch(err => {
        if(statusElem) {
            statusElem.text(`Ops..! Rustmaps have encountered an error!<br>Please try again later.`)
        }
        console.log("Error fetching rust map!")
        return callback(false)
    })
}