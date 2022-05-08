import { extend } from 'duroom/extend';
import DiscussionControls from 'duroom/utils/DiscussionControls';
import Button from 'duroom/components/Button';

import TagDiscussionModal from './components/TagDiscussionModal';

export default function() {
  // Add a control allowing the discussion to be moved to another category.
  extend(DiscussionControls, 'moderationControls', function(items, discussion) {
    if (discussion.canTag()) {
      items.add('tags', <Button icon="fas fa-tag" onclick={() => app.modal.show(TagDiscussionModal, { discussion })}>
        {app.translator.trans('duroom-tags.forum.discussion_controls.edit_tags_button')}
      </Button>);
    }
  });
}
