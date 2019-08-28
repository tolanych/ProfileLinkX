var ProfileLinkX = {
  initialize: function() {
    var cls = ProfileLinkXConfig['class'];
    var mode = ProfileLinkXConfig['mode'];
    var txtarea = ProfileLinkXConfig['textarea'];
    var suggest = document.querySelectorAll(txtarea);
    var elements = document.querySelectorAll('.'+cls);

    if (mode == 'default') {
      if ( elements.length > 0 && typeof window['tippy'] == 'undefined') {
        document.write('<script src="' + ProfileLinkXConfig.jsUrl + 'web/lib/popper.min.js"><\/script>');
        document.write('<script src="' + ProfileLinkXConfig.jsUrl + 'web/lib/tippy.js"><\/script>');
      }

      if ( suggest.length > 0 && typeof window['Textcomplete'] == 'undefined') {
        document.write('<script src="' + ProfileLinkXConfig.jsUrl + 'web/lib/textcomplete.min.js"><\/script>');
      }
    }

    $(document).ready(function() {
      if ( elements.length > 0 )
        ProfileLinkX.initTooltip();
      if ( suggest.length > 0 )
        ProfileLinkX.initSuggest();
    });
  },
  initTooltip: function() {
    var cls = ProfileLinkXConfig['class'];
    tippy('.'+cls, {
      content: '...',
      interactive: true,
      lazy: true,
      onShow(instance) {
        $.ajax({
          url: ProfileLinkXConfig.actionUrl,
          type: 'post',
          dataType: 'json',
          async: true,
          data: {action:'user/one', username:$(instance.reference).attr('attr-user')},
          success: function(response) {
            if (response.success)
              instance.setContent(response.html);
          }
        });
      },
    });
  },
  initSuggest: function() {
    if (!ProfileLinkXConfig['textarea'])
      return;

    var elements = document.querySelectorAll(ProfileLinkXConfig['textarea']);

    [].forEach.call(elements, function(ed) {
      var i = new Textcomplete.editors.Textarea(ed);
      new Textcomplete(i).register([{
        index: 1,
        match: /\B@([а-яё\w.-]*)$/i,
        search: function (term, callback) {
          $.ajax({
            url: ProfileLinkXConfig.actionUrl,
            type: 'post',
            dataType: 'json',
            async: true,
            data: {action:'user/list', search:term},
            success: function(response){
              if (response.success) {
                var i = [];
                for (var a in response.results) {
                  if (response.results.hasOwnProperty(a)) {
                    var s = {
                      tag: response.results[a].username,
                      name: response.results[a].username + (response.results[a].fullname ? ' (' + response.results[a].fullname + ')' : '')
                    };
                  }
                  i.push(s)
                }
                callback(i)
              }
            },
            error: function() {
              callback([]);
            }
          })
        },
        template: function (t) {
          return t.name
        },
        replace: function (t) {
          return '@' + t.tag + ' '
        }
      }])
    })
  }
};
if (typeof ProfileLinkXConfig != 'undefined') {
  ProfileLinkX.initialize();
}