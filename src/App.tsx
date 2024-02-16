import { useEffect, useState } from 'react'
import Content from './components/Content';

const App = () => {
  const [path, setPath] = useState<any>()
  useEffect(() => {
    const queryParamsString = window.location.search;

    if (queryParamsString.includes('page')) {
      const regex = /[?&]page=([^&]*)/;
      const match = queryParamsString.match(regex);
      
      const menuParam = match ? match[1] : null;
      setPath(menuParam)
    }
  }, []);
  if(!path) return <div>Loading</div>
  if(path == 'sample-plugin-content') return <Content />
  return (
    <div>App</div>
  )
}

export default App