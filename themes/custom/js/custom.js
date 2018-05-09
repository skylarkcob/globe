var body = document.getElementsByTagName('body')[0];

(function () {
    var container, searchField;
    container = document.getElementById('header-main');
    searchField = container.getElementsByClassName('search-field')[0];
    searchField.addEventListener('focus', function () {
        if (-1 === container.className.indexOf('nav-menu')) {
            container.className += ' search-opened';
        }
    });
    searchField.addEventListener('focusout', function () {
        var timeout = 0;
        if (searchField.value) {
            timeout = 500;
        }
        setTimeout(function () {
            container.className = container.className.replace(' search-opened', '');
        }, timeout);
    });
})();

(function () {
    var lists = document.querySelectorAll('.menu-item-has-children > a');
    var expandCurrentMenuItem = function (e) {
        [].forEach.call(lists, function (elem) {
            elem.parentNode.className = elem.parentNode.className.replace(' open', '');
        });
        var parent = this.parentNode;
        if (-1 === parent.className.indexOf('open')) {
            e.preventDefault();
            parent.className += ' open';
        } else {
            parent.className = parent.className.replace(' open', '');
        }
    };
    [].map.call(lists, function (elem) {
        elem.addEventListener('click', expandCurrentMenuItem, false);
    });
})();

(function () {
    var mobilePostTabs = document.getElementById('mobile-post-tabs');
    if (null !== mobilePostTabs) {
        var tabs = mobilePostTabs.parentNode,
            currentTab = mobilePostTabs.getElementsByClassName('current')[0];
        currentTab.addEventListener('click', function () {
            if (-1 === tabs.className.indexOf('open')) {
                tabs.className += ' open';
            } else {
                tabs.className = tabs.className.replace(' open', '');
            }
        });
    }
})();

(function () {
    if (-1 !== body.className.indexOf('mobile')) {
        var menuScrollDown = document.getElementsByClassName('menu-scroll-down')[0];

        function scrollBy(distance, duration) {

            var initialY = document.body.scrollTop;
            var y = initialY + distance;
            var baseY = (initialY + y) * 0.5;
            var difference = initialY - baseY;
            var startTime = performance.now();

            function step() {
                var normalizedTime = (performance.now() - startTime) / duration;
                if (normalizedTime > 1) normalizedTime = 1;

                window.scrollTo(0, baseY + difference * Math.cos(normalizedTime * Math.PI));
                if (normalizedTime < 1) window.requestAnimationFrame(step);
            }

            window.requestAnimationFrame(step);
        }

        function findTop(element) {
            var rec = document.getElementById(element).getBoundingClientRect();
            return rec.top + window.scrollY;
        }

        menuScrollDown.addEventListener('click', function (e) {
            e.preventDefault();
            var pos = findTop('colophon'),
                wpadminbar = document.getElementById('wpadminbar');
            if (null !== wpadminbar && wpadminbar.length) {
                pos -= wpadminbar.clientHeight;
            }
            scrollBy(pos, 2000);
        });
    }
})();

function hocwpIsAffUrl(url) {
    if (url.indexOf("mailto") !== -1) {
        return false;
    }

    if (url.indexOf("aff") === -1) {
        return false;
    }

    var tempLink = document.createElement("a");
    tempLink.href = url;

    return tempLink.hostname !== window.location.hostname;
}

function hocwpIsExternal(url) {
    var match = url.match(/^([^:\/?#]+:)?(?:\/\/([^\/?#]*))?([^?#]+)?(\?[^#]*)?(#.*)?/);

    if (match != null && typeof match[1] === 'string' && match[1].length > 0 && match[1].toLowerCase() !== location.protocol) {
        return true;
    }

    return (match != null && typeof match[2] === 'string' && match[2].length > 0 && match[2].replace(new RegExp(':(' + {
            'http:': 80,
            'https:': 443
        }[location.protocol] + ')?$'), '') !== location.host);
}

function hocwpGetHostName(url) {
    var match = url.match(/:\/\/(www[0-9]?\.)?(.[^/:]+)/i);

    if (match != null && match.length > 2 && typeof match[2] === 'string' && match[2].length > 0) {
        return match[2];
    }
    else {
        return null;
    }
}

function hocwpGetDomain(url) {
    var hostName = hocwpGetHostName(url);
    var domain = hostName;

    if (hostName != null) {
        var parts = hostName.split('.').reverse();

        if (parts != null && parts.length > 1) {
            domain = parts[1] + '.' + parts[0];

            if (hostName.toLowerCase().indexOf('.co.uk') != -1 && parts.length > 2) {
                domain = parts[2] + '.' + domain;
            }
        }
    }

    return domain;
}

(function () {
    var container, links, i, len;

    container = document.getElementsByTagName("body")[0];
    links = container.getElementsByTagName("a");

    for (i = 0, len = links.length; i < len; i++) {
        var link = links[i];

        if (hocwpIsAffUrl(link.href) && hocwpIsExternal(link.href)) {
            var rootDomain = hocwpGetDomain(link.href);
        }
    }
})();