import material from 'vue-material/material'
import MdIcon from './MdIcon'

import MdIconHome from './parts/MdIconHome'
import MdIconAdd from './parts/MdIconAdd'
import MdIconApp from './parts/MdIconApp'
import MdIconClose from './parts/MdIconClose'
import MdIconEdit from './parts/MdIconEdit'
import MdIconFilter from './parts/MdIconFilter'
import MdIconMenu from './parts/MdIconMenu'
import MdIconQuote from './parts/MdIconQuote'
import MdIconUser from './parts/MdIconUser'
import MdIconSave from './parts/MdIconSave'
import MdIconSetting from './parts/MdIconSetting'
import MdIconBack from './parts/MdIconBack'
import MdIconMore from './parts/MdIconMore'
import MdIconDelete from './parts/MdIconDelete'

export default Vue => {
  material(Vue)
  Vue.component(MdIcon.name, MdIcon);

  Vue.component(MdIconHome.name, MdIconHome)
  Vue.component(MdIconAdd.name, MdIconAdd)
  Vue.component(MdIconApp.name, MdIconApp)
  Vue.component(MdIconClose.name, MdIconClose)
  Vue.component(MdIconEdit.name, MdIconEdit)
  Vue.component(MdIconFilter.name, MdIconFilter)
  Vue.component(MdIconMenu.name, MdIconMenu)
  Vue.component(MdIconQuote.name, MdIconQuote)
  Vue.component(MdIconUser.name, MdIconUser)
  Vue.component(MdIconSave.name, MdIconSave)
  Vue.component(MdIconSetting.name, MdIconSetting)
  Vue.component(MdIconBack.name, MdIconBack)
  Vue.component(MdIconMore.name, MdIconMore)
  Vue.component(MdIconDelete.name, MdIconDelete)
}