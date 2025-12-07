import { Head, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Shield, CheckCircle2, XCircle } from 'lucide-react';

interface AuthorizeProps {
    client: {
        id: string;
        name: string;
    };
    scopes: string[];
    request: {
        client_id: string;
        redirect_uri: string;
        response_type: string;
        scope: string;
        state: string;
    };
}

const scopeDescriptions: Record<string, string> = {
    openid: 'Verify your identity',
    profile: 'Access your profile information',
    email: 'Access your email address',
    accounting: 'Access accounting system',
    payroll: 'Access payroll system',
    hr: 'Access HR system',
};

export default function Authorize({ client, scopes, request }: AuthorizeProps) {
    const { post, delete: destroy, processing } = useForm(request);

    const approve = () => {
        post('/oauth/authorize');
    };

    const deny = () => {
        destroy('/oauth/authorize');
    };

    return (
        <>
            <Head title="Authorize Application" />
            <div className="flex min-h-screen items-center justify-center p-4 bg-muted/30">
                <Card className="w-full max-w-md">
                    <CardHeader className="text-center">
                        <div className="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                            <Shield className="h-6 w-6 text-primary" />
                        </div>
                        <CardTitle>Authorize Application</CardTitle>
                        <CardDescription>
                            <strong>{client.name}</strong> wants to access your account
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div>
                            <p className="text-sm font-medium mb-2">This application will be able to:</p>
                            <ul className="space-y-2">
                                {scopes.length > 0 ? (
                                    scopes.map((scope) => (
                                        <li key={scope} className="flex items-start gap-2 text-sm">
                                            <CheckCircle2 className="h-4 w-4 text-green-500 mt-0.5 shrink-0" />
                                            <span className="text-muted-foreground">
                                                {scopeDescriptions[scope] || scope}
                                            </span>
                                        </li>
                                    ))
                                ) : (
                                    <li className="text-sm text-muted-foreground">
                                        No specific permissions requested
                                    </li>
                                )}
                            </ul>
                        </div>
                        <div className="pt-4 border-t">
                            <div className="flex gap-2">
                                <Button 
                                    onClick={approve} 
                                    disabled={processing} 
                                    className="flex-1"
                                    size="lg"
                                >
                                    <CheckCircle2 className="h-4 w-4" />
                                    Authorize
                                </Button>
                                <Button 
                                    onClick={deny} 
                                    variant="outline" 
                                    disabled={processing} 
                                    className="flex-1"
                                    size="lg"
                                >
                                    <XCircle className="h-4 w-4" />
                                    Deny
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

