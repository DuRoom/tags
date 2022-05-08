import Model from 'duroom/Model';
import Discussion from 'duroom/models/Discussion';
import IndexPage from 'duroom/components/IndexPage';

import Tag from '../common/models/Tag';
import TagsPage from './components/TagsPage';
import DiscussionTaggedPost from './components/DiscussionTaggedPost';

import TagListState from './states/TagListState';

import addTagList from './addTagList';
import addTagFilter from './addTagFilter';
import addTagLabels from './addTagLabels';
import addTagControl from './addTagControl';
import addTagComposer from './addTagComposer';

app.initializers.add('duroom-tags', function(app) {
  app.routes.tags = {path: '/tags', component: TagsPage };
  app.routes.tag = {path: '/t/:tags', component: IndexPage };

  app.route.tag = tag => app.route('tag', {tags: tag.slug()});

  app.postComponents.discussionTagged = DiscussionTaggedPost;

  app.store.models.tags = Tag;

  app.tagList = new TagListState();

  Discussion.prototype.tags = Model.hasMany('tags');
  Discussion.prototype.canTag = Model.attribute('canTag');

  addTagList();
  addTagFilter();
  addTagLabels();
  addTagControl();
  addTagComposer();
});


// Expose compat API
import tagsCompat from './compat';
import { compat } from '@duroom/core/forum';

Object.assign(compat, tagsCompat);
