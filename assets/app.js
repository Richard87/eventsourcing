/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import * as ReactDOM from "react-dom";
import React from "react"
import Index from "./Index";
import {QueryClient, QueryClientProvider} from "react-query";

export const jsonFetch = async (url, json) => {
    let response = await fetch(url, {
        body: json ? JSON.stringify(json) : null,
        method: json ? "POST" : "GET",
        headers: {accept: "application/json", "content-type": "application/json"}
    });

    const body = await response.json()

    if (response.ok)
        return body

    alert(response.statusText)
    throw new Error(response.statusText, body)
}

const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            queryFn: ({queryKey, pageParam}) => {
                if (pageParam)
                    alert("PageParams is not implemented yet!")

                return jsonFetch(queryKey)
            }
        }
    }
})

function App(){
    return <QueryClientProvider client={queryClient}><Index/></QueryClientProvider>
}

ReactDOM.render(<App />, document.getElementById('root'));