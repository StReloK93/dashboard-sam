import { createI18n } from 'vue-i18n'
import rulocale from './ru'
import uzlocale from './uz'


export default createI18n({
   locale: 'uz',
   fallbackLocale: 'uz',
   messages: {
      ru: rulocale,
      uz: uzlocale
   }
})