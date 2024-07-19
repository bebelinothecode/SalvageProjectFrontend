// import { response } from "express";
import axios from 'axios';

const fetchData = async(url, token) => {
    try {
        const response = await axios.get(url,{
            method:'GET',
            headers: {
                'Content-Type':'application/json',
                'Authorization':`Bearer ${token}`
            },
        });

        console.log(response)

        return response.data;
    } catch (error) {
        console.error("Fetch error:", error);
        throw error;
    }
};


export default fetchData;