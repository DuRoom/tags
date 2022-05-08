import Component from 'duroom/common/Component';
import Button from 'duroom/common/components/Button';
import classList from 'duroom/common/utils/classList';

/**
 * @TODO move to core
 */
export default class ToggleButton extends Component {
  view(vnode) {
    const { className, isToggled, ...attrs } = this.attrs;
    const icon = isToggled ? 'far fa-check-circle' : 'far fa-circle';

    return (
      <Button {...attrs} icon={icon} className={classList([className, isToggled && 'Button--toggled'])}>
        {vnode.children}
      </Button>
    );
  }
}
