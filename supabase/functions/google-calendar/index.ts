import "jsr:@supabase/functions-js/edge-runtime.d.ts";

const corsHeaders = {
  "Access-Control-Allow-Origin": "*",
  "Access-Control-Allow-Methods": "GET, OPTIONS",
  "Access-Control-Allow-Headers": "Content-Type, Authorization, X-Client-Info, Apikey",
};

const API_KEY = Deno.env.get("GOOGLE_CALENDAR_API_KEY") ?? "AIzaSyD4ESybVVkx5lMGtPkbjlJtol0YJB8fYkg";
const CALENDAR_ID = Deno.env.get("GOOGLE_CALENDAR_ID") ?? "pechbonnieu.tiralarc@gmail.com";

Deno.serve(async (req: Request) => {
  if (req.method === "OPTIONS") {
    return new Response(null, { status: 200, headers: corsHeaders });
  }

  try {
    const url = new URL(req.url);
    const timeMin = url.searchParams.get("timeMin") ?? new Date(new Date().getFullYear() - 1, 0, 1).toISOString();
    const timeMax = url.searchParams.get("timeMax") ?? new Date(new Date().getFullYear() + 2, 11, 31).toISOString();

    const gcalUrl =
      `https://www.googleapis.com/calendar/v3/calendars/${encodeURIComponent(CALENDAR_ID)}/events` +
      `?key=${API_KEY}&timeMin=${encodeURIComponent(timeMin)}&timeMax=${encodeURIComponent(timeMax)}` +
      `&singleEvents=true&orderBy=startTime&maxResults=250`;

    const res = await fetch(gcalUrl);
    const data = await res.json();

    if (!res.ok) {
      return new Response(JSON.stringify({ error: data?.error?.message ?? `Erreur ${res.status}` }), {
        status: res.status,
        headers: { ...corsHeaders, "Content-Type": "application/json" },
      });
    }

    return new Response(JSON.stringify(data), {
      headers: { ...corsHeaders, "Content-Type": "application/json" },
    });
  } catch (e: any) {
    return new Response(JSON.stringify({ error: e.message ?? "Erreur interne" }), {
      status: 500,
      headers: { ...corsHeaders, "Content-Type": "application/json" },
    });
  }
});
