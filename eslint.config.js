import globals from 'globals'
import pluginVue from 'eslint-plugin-vue'

import path from 'path'
import { fileURLToPath } from 'url'
import { FlatCompat } from '@eslint/eslintrc'
import pluginJs from '@eslint/js'
import stylistic from '@stylistic/eslint-plugin'

// mimic CommonJS variables -- not needed if using CommonJS
const __filename = fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)
const compat = new FlatCompat({ baseDirectory: __dirname, recommendedConfig: pluginJs.configs.recommended })

export default [
  { languageOptions:
     { globals: globals.browser },
     plugins: {
        '@stylistic': stylistic
     },
      rules: {
          '@stylistic/indent': ['error', 4],
      }
  },
  ...compat.extends(),
  ...pluginVue.configs['flat/vue2-essential']
]
