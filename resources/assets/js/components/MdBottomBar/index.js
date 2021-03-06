import material from 'gmf/material'
import MdBottomBar from './MdBottomBar'
import MdBottomBarItem from './MdBottomBarItem'

export default Vue => {
  material(Vue)
  Vue.component(MdBottomBar.name, MdBottomBar)
  Vue.component(MdBottomBarItem.name, MdBottomBarItem)
}
