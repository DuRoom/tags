import { extend } from 'duroom/extend';
import BasicsPage from 'duroom/components/BasicsPage';

export default function() {
  extend(BasicsPage.prototype, 'homePageItems', items => {
    items.add('tags', {
      path: '/tags',
      label: app.translator.trans('duroom-tags.admin.basics.tags_label')
    });
  });
}
