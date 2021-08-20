define(['uiComponent', 'mage/url', 'jquery'], function (Component, urlBuilder, $) {
    var mixin = {
        handleAutocomplete: function (searchValue) {

            if (searchValue.length >= 5) {
                $.getJSON(this.searchUrl, {
                    sku: searchValue
                }, function (data) {
                    this.searchResult(Object.values(data));
                }.bind(this));
            } else
                this.searchResult([]);
        }
    };
    return function (target) {
        return target.extend(mixin);
    }
});
