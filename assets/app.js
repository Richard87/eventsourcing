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
import {BrowserRouter} from "react-router-dom";
import {Route, Switch} from "react-router";
import Details from "./Details";

export const jsonFetch = async (url, json) => {
    let response = await fetch(url, {
        body: json ? JSON.stringify(json) : null,
        method: json ? "POST" : "GET",
        headers: {accept: "application/json", "content-type": "application/json"}
    });

    const body = response.status !== 202 ? await response.json() : {}

    if (response.ok)
        return body

    if (response.status === 404) {
        console.error(body)
        return
    }

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
    return <QueryClientProvider client={queryClient}>
        <BrowserRouter>
            <Switch>
                <Route exact path={"/building/:uuid"}><Details/></Route>
                <Route><Index/></Route>
            </Switch>
        </BrowserRouter>
    </QueryClientProvider>
}

ReactDOM.render(<App />, document.getElementById('root'));