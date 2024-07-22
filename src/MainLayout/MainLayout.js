import React from 'react';
import Card from '../components/Cards/Card';
// import axios from 'axios';

// const URL = "http://64.23.237.146:3131/api/claims/post-to-salvage";
// const URL = "https://catfact.ninja/fact";



const MainLayout = ({ children }) => {
    // const [data, setData] = useState([]);
    // const token = "13|PHrn3X0vfKQrfGJ1G4KmhzcH2m2I8luMxfriExJi";

    // {
    //     headers:{
    //       Authorization:`Bearer ${token}`,
    //     }
    // }


    // useEffect(() => {
    //     const fetchData = async()=>{
    //         const response = await axios.get(URL);
    //         setData(response.data);
    //         console.log(response.data)
    //     };
    //     fetchData();
    // },[]);


    
 return (
 <div class="flex flex-col min-h-screen">
{/* <SibaHeader/> */}
 <main className='m-5'>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
        <Card
            title="Tesla X"
            buttonText="Bid"
            LearnMore="Details"
            description="This is a broken down tesla car that crashed when it was self driving"
        />
        </div>
        <div>
        <Card
            title="Tesla X"
            buttonText="Bid"
            LearnMore="Details"
            description="This is a broken down tesla car that crashed when it was self driving"
        />
        </div>
        <div>
        <Card
            title="Tesla X"
            buttonText="Bid"
            LearnMore="Details"
            description="This is a broken down tesla car that crashed when it was self driving"
        />
        </div>
        <div>
        <Card
            title="Tesla X"
            buttonText="Bid"
            LearnMore="Details"
            description="This is a broken down tesla car that crashed when it was self driving"
        />
        </div>
        <div>
        <Card
            title="Tesla X"
            buttonText="Bid"
            LearnMore="Details"
            description="This is a broken down tesla car that crashed when it was self driving"
        />
        </div>
        <div>
        <Card
            title="Tesla X"
            buttonText="Bid"
            LearnMore="Details"
            description="This is a broken down tesla car that crashed when it was self driving"
        />
        </div>
    </div>
 </main>
</div>
 );
};

export default MainLayout;
