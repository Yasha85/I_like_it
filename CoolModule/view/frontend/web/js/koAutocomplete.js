define(['uiComponent', 'mage/url', 'jquery'], function (Component, urlBuilder, $) {
    return Component.extend({
        defaults: {
            searchText: '',
            searchUrl: urlBuilder.build('page/ajax/autocomplete'),
            searchResult: []
        },
        initObservable: function () {
            this._super();
            this.observe(['searchText', 'searchResult']);
            return this;
        },
        initialize: function () {
            this._super();
            this.searchText.subscribe(this.handleAutocomplete.bind(this));
        },
        handleAutocomplete: function (searchValue) {
            if (searchValue.length >= 3) {
                $.getJSON(this.searchUrl, {
                    sku: searchValue
                }, function (data) {
                    this.searchResult(Object.values(data));
                }.bind(this));
            } else
                this.searchResult([]);
        }
    });
});
