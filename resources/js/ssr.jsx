import { createInertiaApp } from '@inertiajs/react'
import createServer from '@inertiajs/react/server'
import ReactDOMServer from 'react-dom/server'

createServer(page =>
  createInertiaApp({
    page,
    render: ReactDOMServer.renderToString,
    resolve: name => {
      const pages = import.meta.glob('./Pages/**/*.js', { eager: true })
      return pages[`./Pages/${name}.js`]
    },
    setup: ({ App, props }) => <App {...props} />,
  }),
)