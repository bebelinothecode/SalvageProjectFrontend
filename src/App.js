import './App.css';
import MainLayout from './MainLayout/MainLayout';
import SibaHeader from '../src/components/Header/header';
import Footer from './components/Footer/Footer';
import Disclaimer from './components/Disclaimer/disclaimer';
import TakeALook from './components/TakeALookAround/TakeALook';

export const App =() => {
  return (
    <div>
      <SibaHeader/>
        <TakeALook/>
        <MainLayout/>
        <Disclaimer
          style='bg-white  m-5 flex justify-end'
          buttonStyle='bg-blue-400 text-white px-4 py-2 rounded m-2'
          buttonText='Sales Disclaimer'
        />
      <Footer/>
    </div>
  );
}

// export default App;
